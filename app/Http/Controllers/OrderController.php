<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes de l'utilisateur connecté
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }

    /**
     * Crée une nouvelle commande à partir du panier
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'shipping_name' => 'required|string|max:255',
            'billing_same' => 'boolean',
            'billing_address' => 'required_if:billing_same,false|string|max:255|nullable',
            'billing_city' => 'required_if:billing_same,false|string|max:100|nullable',
            'billing_postal_code' => 'required_if:billing_same,false|string|max:20|nullable',
            'billing_country' => 'required_if:billing_same,false|string|max:100|nullable',
            'payment_method' => 'required|in:stripe,paypal,bank_transfer',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Récupérer le panier de l'utilisateur
        $cart = $user->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Vérifier le stock des produits
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient stock for product: ' . $item->product->name,
                    'product_id' => $item->product_id
                ], 400);
            }
        }

        // Démarrer une transaction de base de données
        DB::beginTransaction();

        try {
            // Créer la commande
            $orderData = [
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'amount' => $cart->total,
                'shipping_cost' => $cart->shipping_cost,
                'billing_name' => $request->billing_same ? $request->shipping_name : $request->billing_name,
                'billing_email' => $user->email,
                'billing_phone' => $request->billing_same ? $request->shipping_phone : $request->billing_phone,
                'billing_address' => $request->billing_same ? $request->shipping_address : $request->billing_address,
                'billing_city' => $request->billing_same ? $request->shipping_city : $request->billing_city,
                'billing_postal_code' => $request->billing_same ? $request->shipping_postal_code : $request->billing_postal_code,
                'billing_country' => $request->billing_same ? $request->shipping_country : $request->billing_country,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_country' => $request->shipping_country,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ];

            $order = Order::create($orderData);

            // Ajouter les articles de la commande
            foreach ($cart->items as $item) {
                $orderItem = new OrderItem([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'product_description' => $item->product->description,
                    'unit_price' => $item->price,
                    'quantity' => $item->quantity,
                    'tax_rate' => $item->tax_rate,
                    'tax_amount' => $item->tax_amount,
                    'discount_amount' => $item->discount_amount,
                    'weight' => $item->product->weight,
                    'status' => 'pending',
                ]);

                $order->items()->save($orderItem);

                // Mettre à jour le stock du produit
                $item->product->decrement('stock', $item->quantity);
            }

            // Vider le panier
            $cart->items()->delete();
            $cart->update(['total' => 0, 'tax' => 0, 'shipping_cost' => 0]);

            // Valider la transaction
            DB::commit();

            // Préparer la réponse
            $order->load('items.product');

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            DB::rollBack();
            
            Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create order',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Affiche les détails d'une commande spécifique
     */
    public function show(Order $order): JsonResponse
    {
        // Vérifier que l'utilisateur est autorisé à voir cette commande
        if (Auth::id() !== $order->user_id && !Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $order->load(['items.product', 'user']);

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }

    /**
     * Met à jour le statut d'une commande (pour les administrateurs)
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Admin access required'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'shipping_method' => 'nullable|string|max:100',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date|after_or_equal:shipped_at',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $order->update($request->only([
            'status', 'tracking_number', 'shipping_method', 'shipped_at', 'delivered_at', 'notes'
        ]));

        // Si la commande est annulée, remettre les produits en stock
        if ($request->has('status') && $request->status === 'cancelled' && $order->wasChanged('status')) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        $order->load('items.product');

        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Annule une commande (pour l'utilisateur)
     */
    public function cancel(Order $order): JsonResponse
    {
        // Vérifier que l'utilisateur est autorisé à annuler cette commande
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Vérifier que la commande peut être annulée
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Mettre à jour le statut de la commande
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Remettre les produits en stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order cancelled successfully',
                'data' => $order->fresh('items.product')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Order cancellation failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cancel order',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Liste toutes les commandes (pour les administrateurs)
     */
    public function allOrders(Request $request): JsonResponse
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Admin access required'
            ], 403);
        }

        $query = Order::with(['user', 'items.product']);

        // Filtrage
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Tri
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_dir', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }
}

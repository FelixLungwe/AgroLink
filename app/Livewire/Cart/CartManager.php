<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class CartManager extends Component
{
    protected $listeners = ['addToCart', 'cartUpdated' => '$refresh'];
    
    public $cart = [];
    public $totalItems = 0;
    public $totalAmount = 0;
    public $isOpen = false;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = Session::get('cart', []);
        $this->calculateTotals();
    }

    public function saveCart()
    {
        Session::put('cart', $this->cart);
        Session::save();
        $this->emit('cartUpdated');
    }

    public function addToCart($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        
        // Vérifier le stock
        if ($product->stock < $quantity) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Stock insuffisant pour ce produit.'
            ]);
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity'] += $quantity;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price_kg,
                'unit' => $product->unit,
                'photo' => $product->photo_url,
                'quantity' => $quantity,
                'max_quantity' => $product->stock
            ];
        }
        
        $this->saveCart();
        
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Produit ajouté au panier !'
        ]);
    }

    public function updateQuantity($productId, $quantity)
    {
        $quantity = (int) $quantity;
        
        if ($quantity <= 0) {
            $this->removeItem($productId);
            return;
        }

        // Vérifier le stock maximum
        $maxQuantity = $this->cart[$productId]['max_quantity'] ?? 0;
        if ($quantity > $maxQuantity) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Quantité maximale disponible : ' . $maxQuantity
            ]);
            $quantity = $maxQuantity;
        }

        $this->cart[$productId]['quantity'] = $quantity;
        $this->saveCart();
    }

    public function checkout()
    {
        if (!auth()->check()) {
            return redirect()->route('login', ['redirect' => 'checkout']);
        }

        // Vérifier le stock avant le paiement
        foreach ($this->cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $item['quantity']) {
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'error',
                    'message' => 'Stock insuffisant pour : ' . $item['name']
                ]);
                return;
            }
        }

        // Configuration de Stripe
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $line_items = [];
        foreach ($this->cart as $productId => $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                        'description' => 'Quantité: ' . $item['quantity'] . ' ' . ($item['unit'] ?? 'unité'),
                    ],
                    'unit_amount' => (int)($item['price'] * 100), // Convertir en centimes
                ],
                'quantity' => $item['quantity'],
            ];
        }

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cart'),
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'user_id' => auth()->id(),
                    'cart_total' => $this->totalAmount,
                ]
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Erreur lors de la création de la session de paiement: ' . $e->getMessage()
            ]);
            return;
        }
    }

    public function removeItem($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            $this->saveCart();
            
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Produit retiré du panier.'
            ]);
        }
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->saveCart();
    }

    public function calculateTotals()
    {
        $this->totalItems = collect($this->cart)->sum('quantity');
        $this->totalAmount = collect($this->cart)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }
    
    public function openCart()
    {
        $this->isOpen = true;
        $this->loadCart();
    }


    public function render()
    {
        $this->calculateTotals();
        return view('livewire.cart.cart-manager', [
            'cartItems' => collect($this->cart)
        ]);
    }
}
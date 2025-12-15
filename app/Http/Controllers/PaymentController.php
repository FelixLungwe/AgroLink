<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }
        
        return view('checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Ici, vous intégrerez votre solution de paiement (Stripe, PayPal, etc.)
        // Pour l'instant, nous allons simuler un paiement réussi
        
        // Créer la commande
        $order = Auth::user()->orders()->create([
            'total' => array_reduce($cart, function($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0),
            'status' => 'completed'
        ]);
        
        // Ajouter les produits à la commande
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }
        
        // Vider le panier
        session()->forget('cart');
        
        return redirect()->route('payment.success', $order);
    }

    public function success()
    {
        return view('payment.success');
    }
}
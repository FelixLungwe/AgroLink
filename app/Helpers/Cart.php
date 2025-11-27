<?php

namespace App\Helpers;

class Cart
{
    public static function add($product, $qty = 1)
    {
        $cart = session('cart', []);

        $id = $product->id;
        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price_kg,
                'unit' => $product->unit,
                'photo' => $product->photo_url,
                'producer' => $product->producer->name,
                'freshness' => $product->freshness,
                'qty' => $qty,
            ];
        }

        session(['cart' => $cart]);
    }

    public static function items()
    {
        return session('cart', []);
    }

    public static function count()
    {
        return collect(session('cart', []))->sum('qty');
    }

    public static function total()
    {
        return collect(session('cart', []))->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public static function remove($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
    }

    public static function updateQty($productId, $qty)
    {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $qty;
            if ($qty <= 0) unset($cart[$productId]);
        }
        session(['cart' => $cart]);
    }

    public static function clear()
    {
        session()->forget('cart');
    }
}
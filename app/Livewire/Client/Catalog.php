<?php

namespace App\Livewire\Client;

use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class Catalog extends Component
{
    public $search = '';

    // ← Ajoute cette méthode magique
    public function __invoke()
    {
        return $this->render();
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        Cart::add($product, 1);

        $this->dispatch('cart-updated');
        $this->dispatch('toast', ['message' => "$product->name ajouté au panier !", 'type' => 'success']);
    }

    public function render()
    {
        $products = Product::with('producer')
            ->where('available', true)
            ->where('stock', '>', 0)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->inRandomOrder()
            ->limit(20)
            ->get();

        return view('livewire.client.catalog', compact('products'));
    }

    public function removeFromCart($productId)
    {
        Cart::remove($productId);
        $this->dispatch('cart-updated');
    }
}

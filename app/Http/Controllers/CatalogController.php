<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('producer')
            ->where('available', true)
            ->where('stock', '>', 0);

        // Filtre par recherche
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre bio
        if ($request->has('bio')) {
            $query->where('bio', true);
        }

        // Trier par prix
        if ($sort = $request->input('sort')) {
            $direction = $request->input('direction', 'asc');
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        return view('catalog', compact('products'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('producer_id', $product->producer_id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}

<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('producer.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // Dans app/Http/Controllers/Producer/ProductController.php

public function store(Request $request)
{
    // Récupérer les données validées
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:2048',
        'is_available' => 'sometimes|boolean',
        'is_featured' => 'sometimes|boolean',
        'is_bestseller' => 'sometimes|boolean',
        'is_new' => 'sometimes|boolean',
        'bio' => 'sometimes|boolean',
        'compare_at_price' => 'nullable|numeric|min:0|gt:price',
        'unit' => 'required|string|in:kg,pièce,botte,barquette,pot',
        'stock_threshold' => 'nullable|integer|min:0',
        'harvested_at' => 'nullable|date',
    ]);

    // Gérer le téléchargement de l'image
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $validated['image_url'] = 'storage/' . $path;
    }

    // Définir les valeurs par défaut
    $validated['producer_id'] = auth()->id();
    $validated['quantity'] = $validated['stock']; // Synchroniser quantité et stock
    $validated['stock_status'] = $validated['stock'] > 0 ? 'in_stock' : 'out_of_stock';
    
    // Gérer les champs booléens
    $booleans = ['is_available', 'is_featured', 'is_bestseller', 'is_new', 'bio'];
    foreach ($booleans as $field) {
        $validated[$field] = $request->has($field) ? (bool)$request->input($field) : false;
    }

    // Créer le produit
    $product = Product::create($validated);

    return redirect()
        ->route('producer.products')
        ->with('success', 'Produit créé avec succès !');
}
}

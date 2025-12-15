<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::query()
            ->where('available', true)
            ->where('stock', '>', 0)
            ->with('producer')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('welcome', compact('featuredProducts'));
    }
}

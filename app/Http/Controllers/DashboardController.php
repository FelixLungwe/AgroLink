<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistiques générales
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('stock', '<=', 5)->count(),
            'total_customers' => User::where('is_admin', false)->count(),
        ];
        
        // Produits disponibles pour la page d'accueil
        $featuredProducts = Product::where('available', true)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Commandes récentes
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(5)
            ->get();

        // Produits les plus vendus
        $topProducts = Product::withCount(['orderItems as sales_count' => function($query) {
            $query->select(DB::raw('sum(quantity)'));
        }])
        ->orderBy('sales_count', 'desc')
        ->take(5)
        ->get();

        // Données pour le graphique des ventes
        $salesData = [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            'data' => [
                rand(1000, 5000), rand(1000, 5000), rand(1000, 5000), 
                rand(1000, 5000), rand(1000, 5000), rand(1000, 5000),
                rand(1000, 5000), rand(1000, 5000), rand(1000, 5000),
                rand(1000, 5000), rand(1000, 5000), rand(1000, 5000)
            ]
        ];

        return view('dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'salesData' => $salesData,
            'user' => $user
        ]);
    }
}

<?php

namespace App\Livewire\Producer;

use App\Models\Order;
use Livewire\Component;

class Dashboard extends Component
{
    public $caToday = 0;
    public $caWeek = 0;
    public $totalOrders = 0;
    public $pendingOrders = 0;

    // // ← Ajoute cette méthode magique
    // public function __invoke()
    // {
    //     return $this->render();
    // }

    public function mount()
    {
        $this->caToday = Order::where('producer_id', auth()->id())
            ->whereDate('created_at', today())
            ->where('status', 'paid')
            ->sum('total');

        $this->caWeek = Order::where('producer_id', auth()->id())
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'paid')
            ->sum('total');

        $this->totalOrders = Order::where('producer_id', auth()->id())->count();
        $this->pendingOrders = Order::where('producer_id', auth()->id())
            ->whereIn('status', ['paid', 'preparing'])
            ->count();
    }

    public function render()
    {
        $recentOrders = Order::with('customer')
            ->where('producer_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.producer.dashboard', [
            'recentOrders' => $recentOrders
        ])->layout('layouts.app', ['title' => 'Tableau de bord']);
    }
}
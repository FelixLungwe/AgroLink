<?php

namespace App\Livewire\Producer;

use App\Models\Order;
use Livewire\Component;

class Orders extends Component
{
    public $statusFilter = 'all';

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::where('producer_id', auth()->id())->findOrFail($orderId);
        $order->update(['status' => $newStatus]);

        if ($newStatus === 'ready') {
            // Ici tu pourras envoyer une notification au client plus tard
        }

        $this->dispatch('toast', 'Statut mis à jour !');
    }

    public function render()
    {
        $orders = Order::with(['customer', 'items.product'])
            ->where('producer_id', auth()->id())
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.producer.orders', compact('orders'))
            ->layout('layouts.app', ['title' => 'Mes commandes']);
    }
}

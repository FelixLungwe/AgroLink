<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Mes commandes</h1>

        <select wire:model.live="statusFilter" class="px-6 py-3 border rounded-xl">
            <option value="all">Toutes les commandes</option>
            <option value="paid">Payées</option>
            <option value="preparing">En préparation</option>
            <option value="ready">Prêtes</option>
            <option value="delivered">Livrées</option>
        </select>
    </div>

    @forelse($orders as $order)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-emerald-700 text-white p-4 flex justify-between items-center">
                <div>
                    <p class="text-lg font-bold">Commande #{{ $order->id }}</p>
                    <p class="text-sm opacity-90">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold">{{ number_format($order->total, 2) }} €</p>
                    <span class="px-4 py-1 rounded-full text-sm font-bold {{ $order->status_badge }}">
                        {{ $order->status_text }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="font-semibold text-gray-700">Client</p>
                        <p>{{ $order->customer->name }} — {{ $order->customer->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Retrait / Livraison</p>
                        <p>{{ $order->delivery_method === 'pickup' ? 'Retrait à la ferme' : 'Livraison à :' }}</p>
                        @if($order->delivery_method === 'delivery')
                            <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
                        @endif
                    </div>
                </div>

                <div class="border rounded-xl overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm">Produit</th>
                                <th class="px-4 py-3 text-center text-sm">Qté</th>
                                <th class="px-4 py-3 text-right text-sm">Prix unitaire</th>
                                <th class="px-4 py-3 text-right text-sm">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 text-center">{{ $item->quantity }} {{ $item->unit }}</td>
                                <td class="px-4 py-3 text-right">{{ $item->price_at_purchase }} €</td>
                                <td class="px-4 py-3 text-right font-semibold">{{ $item->subtotal }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    @if($order->status === 'paid')
                        <button wire:click="updateStatus({{ $order->id }}, 'preparing')"
                                class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700">
                            Marquer en préparation
                        </button>
                    @endif
                    @if($order->status === 'preparing')
                        <button wire:click="updateStatus({{ $order->id }}, 'ready')"
                                class="bg-green-600 text-white px-8 py-3 rounded-xl hover:bg-green-700 font-bold">
                            Commande prête !
                        </button>
                    @endif
                    @if($order->status === 'ready')
                        <button wire:click="updateStatus({{ $order->id }}, 'delivered')"
                                class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700">
                            Marquer comme livrée
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20 text-gray-500">
            <i class="ri-inbox-line text-6xl mb-4"></i>
            <p class="text-xl">Aucune commande pour le moment</p>
        </div>
    @endforelse

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>
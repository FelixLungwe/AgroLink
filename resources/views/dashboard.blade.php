<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }
        .chart-container canvas {
            max-width: 100%;
            height: auto !important;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(isset($stats))
            <!-- Cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="ri-shopping-cart-2-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Commandes totales</p>
                            <h3 class="text-2xl font-bold">{{ number_format($stats['total_orders'] ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="ri-time-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">En attente</p>
                            <h3 class="text-2xl font-bold">{{ $stats['pending_orders'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="ri-check-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Terminées</p>
                            <h3 class="text-2xl font-bold">{{ $stats['completed_orders'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <i class="ri-box-3-line text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Produits</p>
                            <h3 class="text-2xl font-bold">{{ $stats['total_products'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Graphique des ventes et produits populaires -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                    <h2 class="text-xl font-semibold mb-6">Ventes mensuelles</h2>
                    <div class="chart-container">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6">Produits populaires</h2>
                    <div class="space-y-4">
                        @forelse($topProducts ?? [] as $product)
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden mr-4">
                                @if(isset($product->image_url))
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="ri-image-line text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium">{{ $product->name ?? 'Produit inconnu' }}</h4>
                                <p class="text-sm text-gray-500">{{ $product->sales_count ?? 0 }} ventes</p>
                            </div>
                            <span class="text-green-600 font-semibold">
                                {{ isset($product->price) ? number_format($product->price, 2, ',', ' ') . ' €' : 'N/A' }}
                            </span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Aucun produit populaire pour le moment</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Dernières commandes -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold">Dernières commandes</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Commande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Anonyme' }}</div>
                                    @if(isset($order->user->email))
                                    <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'Date inconnue' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $order->status ?? 'pending';
                                        $statusClasses = [
                                            'completed' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusClass = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ isset($order->total) ? number_format($order->total, 2, ',', ' ') . ' €' : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Aucune commande récente
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique des ventes
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($salesData['labels'] ?? []),
                        datasets: [{
                            label: 'Ventes mensuelles',
                            data: @json($salesData['data'] ?? []),
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 10,
                                right: 10,
                                top: 10,
                                bottom: 10
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Ventes: ' + context.parsed.y.toLocaleString('fr-FR') + ' €';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('fr-FR') + ' €';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
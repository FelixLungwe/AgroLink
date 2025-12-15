@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- En-tête de la commande -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Commande #{{ $order->id }}</h1>
                        <p class="text-gray-500">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ][$order->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Détails de la commande -->
                    <div class="lg:col-span-2">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Articles commandés</h2>
                        <div class="bg-gray-50 rounded-lg overflow-hidden">
                            @foreach($order->items as $item)
                            <div class="p-4 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-20 w-20 rounded-md overflow-hidden bg-gray-200">
                                        @if($item->product->photo)
                                            <img src="{{ $item->product->photo_url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-400">
                                                <i class="ri-image-line text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3>{{ $item->product->name }}</h3>
                                            <p class="ml-4">{{ number_format($item->unit_price * $item->quantity, 2, ',', ' ') }} €</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Quantité : {{ $item->quantity }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Prix unitaire : {{ number_format($item->unit_price, 2, ',', ' ') }} €
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Informations de livraison -->
                        <div class="mt-8">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">Informations de livraison</h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Adresse de livraison</h3>
                                        <address class="mt-1 text-sm not-italic">
                                            {{ $order->user->name }}<br>
                                            {{ $order->shipping_address }}<br>
                                            {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
                                            {{ $order->shipping_country }}
                                        </address>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Méthode de livraison</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            @if($order->shipping_method === 'standard')
                                                Livraison standard
                                            @elseif($order->shipping_method === 'express')
                                                Livraison express
                                            @else
                                                {{ $order->shipping_method }}
                                            @endif
                                        </p>
                                        @if($order->tracking_number)
                                            <p class="mt-2 text-sm">
                                                <span class="font-medium text-gray-500">N° de suivi :</span>
                                                <span class="text-gray-900">{{ $order->tracking_number }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Récapitulatif -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Récapitulatif</h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sous-total</span>
                                    <span class="font-medium">{{ number_format($order->amount - $order->shipping_cost, 2, ',', ' ') }} €</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4">
                                    <span class="text-gray-600">Frais de livraison</span>
                                    <span class="font-medium">{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-4 text-lg font-bold">
                                    <span>Total TTC</span>
                                    <span class="text-green-600">{{ number_format($order->amount, 2, ',', ' ') }} €</span>
                                </div>
                            </div>

                            <div class="mt-8">
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Méthode de paiement</h3>
                                <div class="flex items-center">
                                    <i class="ri-bank-card-line text-2xl text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900">
                                        @if($order->payment_method === 'card')
                                            Carte bancaire ({{ $order->card_last_four ? '•••• ' . $order->card_last_four : 'Stripe' }})
                                        @elseif($order->payment_method === 'paypal')
                                            PayPal
                                        @else
                                            {{ ucfirst($order->payment_method) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            @if($order->status === 'pending' || $order->status === 'processing')
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <a href="#" class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="ri-close-line mr-2"></i> Annuler la commande
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Suivi de commande -->
                        <div class="mt-6 bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Suivi de commande</h3>
                            <div class="space-y-4">
                                @php
                                    $steps = [
                                        'pending' => [
                                            'label' => 'Commande reçue',
                                            'description' => 'Votre commande a été enregistrée et est en attente de traitement.',
                                            'active' => true,
                                            'completed' => true,
                                        ],
                                        'processing' => [
                                            'label' => 'En préparation',
                                            'description' => 'Votre commande est en cours de préparation par nos équipes.',
                                            'active' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                                            'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                                        ],
                                        'shipped' => [
                                            'label' => 'Expédiée',
                                            'description' => 'Votre commande a été expédiée.',
                                            'active' => in_array($order->status, ['shipped', 'delivered']),
                                            'completed' => in_array($order->status, ['shipped', 'delivered']),
                                        ],
                                        'delivered' => [
                                            'label' => 'Livrée',
                                            'description' => 'Votre commande a été livrée.',
                                            'active' => $order->status === 'delivered',
                                            'completed' => $order->status === 'delivered',
                                        ],
                                    ];
                                @endphp

                                @foreach($steps as $status => $step)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @if($step['completed'])
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                                    <i class="ri-check-line text-green-600"></i>
                                                </div>
                                            @elseif($step['active'])
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                                    <i class="ri-loader-4-line text-blue-600 animate-spin"></i>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100">
                                                    <i class="ri-check-line text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium {{ $step['active'] ? 'text-gray-900' : 'text-gray-500' }}">
                                                {{ $step['label'] }}
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $step['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="ml-4 h-6 border-l-2 border-gray-200"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="{{ route('profile.orders') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                        <i class="ri-arrow-left-line mr-2"></i> Retour à mes commandes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

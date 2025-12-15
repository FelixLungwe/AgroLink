@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- En-tête de confirmation -->
        <div class="bg-green-500 text-white p-6 text-center">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-checkbox-circle-line text-4xl"></i>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Paiement réussi !</h1>
            <p class="text-green-100">Merci pour votre commande #{{ $order->id }}</p>
        </div>

        <!-- Détails de la commande -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold mb-4">Récapitulatif de votre commande</h2>
            
            <div class="space-y-4">
                @foreach($orderItems as $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                            @if($item->product->photo)
                                <img src="{{ $item->product->photo_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="ri-image-line text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->quantity }} × {{ number_format($item->unit_price, 2, ',', ' ') }} €</p>
                        </div>
                    </div>
                    <span class="font-medium">{{ number_format($item->quantity * $item->unit_price, 2, ',', ' ') }} €</span>
                </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Sous-total</span>
                    <span class="font-medium">{{ number_format($order->amount, 2, ',', ' ') }} €</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Livraison</span>
                    <span class="font-medium">Gratuite</span>
                </div>
                <div class="flex justify-between text-lg font-bold mt-4 pt-4 border-t border-gray-100">
                    <span>Total TTC</span>
                    <span class="text-green-600">{{ number_format($order->amount, 2, ',', ' ') }} €</span>
                </div>
            </div>
        </div>

        <!-- Informations de livraison -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold mb-4">Informations de livraison</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Adresse de livraison</h3>
                    <p class="text-gray-600">
                        {{ auth()->user()->name }}<br>
                        {{ auth()->user()->address }}<br>
                        {{ auth()->user()->postal_code }} {{ auth()->user()->city }}<br>
                        {{ auth()->user()->country }}
                    </p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">Méthode de livraison</h3>
                    <p class="text-gray-600">
                        Livraison standard<br>
                        Délai de livraison estimé : 2-3 jours ouvrés
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center">
            <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto mb-4 sm:mb-0 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                <i class="ri-arrow-left-line mr-2"></i> Retour à la boutique
            </a>
            <a href="{{ route('profile.orders') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                Voir mes commandes
            </a>
        </div>
    </div>

    <!-- Section produits recommandés -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Vous aimerez peut-être aussi</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($recommendedProducts as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                <a href="{{ route('products.show', $product) }}" class="block">
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        @if($product->photo)
                            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="ri-image-line text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1 line-clamp-1">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-green-600 font-bold">{{ number_format($product->price_kg, 2, ',', ' ') }} €</span>
                            @if($product->bio)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Bio</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Désactiver le bouton de retour du navigateur pour éviter les soumissions multiples
    if (window.history && window.history.pushState) {
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, null, window.location.href);
        };
    }
</script>
@endpush
@endsection

<div class="min-h-screen bg-gray-50">

    <!-- Hero + Recherche -->
    <section class="bg-gradient-to-r from-green-600 to-emerald-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-6">Produits frais & locaux</h1>
            <div class="max-w-2xl mx-auto">
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Rechercher tomates, miel, œufs..."
                       class="w-full px-6 py-4 rounded-full text-gray-800 text-lg focus:outline-none">
            </div>
        </div>
    </section>

    <!-- Grille produits -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        @if($products->count() == 0)
            <p class="text-center text-gray-600 text-xl py-20">Aucun produit trouvé...</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-3">
                        <div class="relative">
                            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}"
                                 class="w-full h-56 object-cover">
                            <div class="absolute top-3 right-3">
                                @if($product->bio)
                                    <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">BIO</span>
                                @endif
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <span class="bg-white/90 text-green-700 px-3 py-1 rounded-full text-xs font-bold backdrop-blur">
                                    {{ $product->freshness }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-3">
                                <i class="ri-map-pin-line"></i> {{ $product->producer->name }} • {{ rand(3,25) }} km
                            </p>

                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-3xl font-bold text-green-600">
                                        {{ number_format($product->price_kg, 2) }} €
                                        <span class="text-sm font-normal text-gray-600">/{{ $product->unit }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500">Stock : {{ $product->stock }} dispo</p>
                                </div>

                                <button wire:click="addToCart({{ $product->id }})"
                                        class="bg-green-600 text-white p-4 rounded-full hover:bg-green-700 transition shadow-lg hover:shadow-xl transform hover:scale-110">
                                    <i class="ri-shopping-cart-2-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- PANIER FLOTTANT ANIMÉ (compatible session) -->
<div x-data="{ open: false }" 
     @cart-updated.window="open = true; setTimeout(() => open = false, 4000)"
     class="fixed bottom-6 right-6 z-50">

    <!-- Bouton panier -->
    <button @click="open = !open"
            class="relative bg-green-600 text-white p-5 rounded-full shadow-2xl hover:bg-green-700 transition">
        <i class="ri-shopping-cart-2-line text-3xl"></i>
        @if(Cart::count() > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-8 w-8 flex items-center justify-center animate-bounce">
                {{ Cart::count() }}
            </span>
        @endif
    </button>

    <!-- Contenu panier -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4"
         class="absolute bottom-20 right-0 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden">

        <div class="bg-gradient-to-r from-green-600 to-emerald-700 text-white p-4 flex justify-between items-center">
            <h3 class="font-bold text-lg">Votre panier ({{ Cart::count() }})</h3>
            <button @click="open = false" class="text-white hover:text-gray-200">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>

        <div class="max-h-96 overflow-y-auto p-4">
            @forelse(Cart::items() as $id => $item)
                <div class="flex gap-4 py-3 border-b last:border-0">
                    <img src="{{ $item['photo'] }}" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-600">{{ $item['producer'] }}</p>
                        <p class="text-xs text-green-600">{{ $item['freshness'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">{{ $item['price'] }} €</p>
                        <p class="text-xs">×{{ $item['qty'] }}</p>
                        <button wire:click="$set('dummy', '{{ $id }}')" 
                                onclick="Livewire.emit('removeFromCart', '{{ $id }}')"
                                class="text-red-500 text-xs hover:underline">Supprimer</button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-10">Votre panier est vide</p>
            @endforelse
        </div>

        <div class="p-4 bg-gray-50 border-t">
            <div class="flex justify-between mb-3">
                <span class="font-bold text-lg">Total</span>
                <span class="font-bold text-2xl text-green-600">{{ number_format(Cart::total(), 2) }} €</span>
            </div>
            <a href="{{ route('cart.index') }}" 
               class="block text-center bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 transition">
                Voir le panier →
            </a>
        </div>
    </div>
</div>

{{-- Écoute l’événement pour supprimer --}}
@push('scripts')
<script>
    Livewire.on('removeFromCart', (productId) => {
        @this.call('removeFromCart', productId)
    });
</script>
@endpush
</div>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fermes Directes - Produits frais locaux</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://js.stripe.com/v3/"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body class="bg-gray-50 font-sans antialiased">

@livewire('cart.cart-manager')

{{-- Popup Détail Produit --}}
<div x-data="{ productDetail: null }" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none">
    <div x-show="productDetail"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-70 pointer-events-auto"
         @click="productDetail = null">
    </div>

    <div x-show="productDetail"
         x-transition:enter="transition ease-out duration-500 transform"
         x-transition:enter-start="opacity-0 scale-75 translate-y-10"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-end="opacity-0 scale-75 translate-y-10"
         class="relative bg-white rounded-3xl shadow-2xl max-w-5xl w-full mx-4 pointer-events-auto overflow-hidden"
         @click.stop>
         
        <button @click="productDetail = null" class="absolute top-6 right-6 text-gray-500 hover:text-gray-800 z-10">
            <i class="ri-close-line text-4xl"></i>
        </button>

        <template x-if="productDetail">
            <div class="grid md:grid-cols-2">
                <!-- Image -->
                <div class="bg-gradient-to-br from-green-100 to-emerald-200 p-12 flex items-center justify-center">
                    <div class="bg-white bg-opacity-80 rounded-3xl p-12 shadow-xl">
                        <i class="ri-fruit-plant-line text-9xl text-green-600"></i>
                        <!-- Tu pourras remplacer par <img> plus tard -->
                    </div>
                </div>

                <!-- Détails -->
                <div class="p-10 md:p-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4" x-text="productDetail.nom"></h2>
                    <p class="text-3xl font-bold text-green-600 mb-6" x-text="productDetail.prix"></p>

                    <div class="space-y-4 text-gray-700 mb-8">
                        <p><strong>Ferme :</strong> <span x-text="productDetail.ferme"></span></p>
                        <p><strong>Fraîcheur :</strong> <span class="text-green-600 font-bold" x-text="productDetail.frais"></span></p>
                        <p><strong>Origine :</strong> Produit local (moins de 30 km)</p>
                        <p><strong>Saison :</strong> En pleine saison</p>
                    </div>

                    <div class="flex items-center gap-4 mb-8">
                        <button @click="$dispatch('add-to-cart', { id: productDetail.id, name: productDetail.nom, price: productDetail.prix })"
                                class="bg-green-600 hover:bg-green-700 text-white px-10 py-5 rounded-full font-bold text-lg transition shadow-lg flex items-center gap-3">
                            <i class="ri-shopping-cart-2-fill"></i>
                            Ajouter au panier
                        </button>
                    </div>

                    <form x-bind:action="'/cart/add/' + productDetail.id" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                            <i class="ri-shopping-cart-line mr-2"></i> Ajouter au panier
                        </button>
                    </form>

                    <p class="text-sm text-gray-500">
                        Livraison ou retrait gratuit à la ferme • Paiement sécurisé
                    </p>
                </div>
            </div>
        </template>
    </div>
</div>

{{-- Header, Hero, Recherche... (identique à avant) --}}
<header class="bg-white shadow-sm sticky top-0 z-40">...</header>

{{-- Hero --}}
<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-green-600 to-emerald-700 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542838132-92d533f92e0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80')] bg-cover bg-center"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">Découvrez des produits frais et locaux</h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Commandez directement auprès des producteurs de votre région et recevez des produits frais livrés chez vous.</p>
        <a href="{{ route('catalog.index') }}" class="inline-block bg-white text-green-700 font-semibold px-8 py-4 rounded-full hover:bg-gray-100 transition duration-300 transform hover:scale-105">
            Voir le catalogue
        </a>
    </div>
</section>

<!-- Catégories -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Nos catégories</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="{{ route('catalog.index') }}?category=fruits" class="bg-green-50 rounded-xl p-6 text-center hover:shadow-lg transition duration-300">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-apple-fill text-3xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-lg">Fruits</h3>
            </a>
            <a href="{{ route('catalog.index') }}?category=legumes" class="bg-green-50 rounded-xl p-6 text-center hover:shadow-lg transition duration-300">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-leaf-fill text-3xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-lg">Légumes</h3>
            </a>
            <a href="{{ route('catalog.index') }}?category=viandes" class="bg-green-50 rounded-xl p-6 text-center hover:shadow-lg transition duration-300">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-restaurant-2-fill text-3xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-lg">Viandes</h3>
            </a>
            <a href="{{ route('catalog.index') }}?category=produits-laitiers" class="bg-green-50 rounded-xl p-6 text-center hover:shadow-lg transition duration-300">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="ri-cup-fill text-3xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-lg">Produits Laitiers</h3>
            </a>
        </div>
    </div>
</section>

<!-- Produits en vedette -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-3xl font-bold">Produits populaires</h2>
            <a href="{{ route('catalog.index') }}" class="text-green-600 hover:text-green-800 font-medium flex items-center">
                Voir tout <i class="ri-arrow-right-line ml-2"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="relative">
                        @if($product->isOnSale())
                            <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4">{{ $product->producer->name ?? 'Producteur' }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-green-600">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                            <button @click="$dispatch('add-to-cart', { 
                                id: {{ $product->id }}, 
                                name: '{{ addslashes($product->name) }}', 
                                price: {{ $product->price }} 
                            })" class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-full transition shadow-lg">
                                <i class="ri-add-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Écouteurs Alpine pour le détail et le panier --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Écoute l'événement pour ouvrir le détail produit
        window.addEventListener('open-product-detail', event => {
            const alpineElement = document.querySelector('[x-data="{ productDetail: null }"]');
            if (alpineElement && alpineElement.__x) {
                alpineElement.__x.$data.productDetail = event.detail;
            }
        });

        // Écoute l'événement pour ajouter au panier
        window.addEventListener('add-to-cart', event => {
            const { id, name, price } = event.detail;

            // On envoie l'événement directement au composant Livewire
            Livewire.emit('addToCart', id, name, price);

            // Optionnel : petit feedback visuel
            const btn = event.target.closest('button');
            if (btn) {
                btn.innerHTML = '<i class="ri-check-line text-xl"></i>';
                setTimeout(() => {
                    btn.innerHTML = '<i class="ri-shopping-cart-2-fill text-xl"></i>';
                }, 800);
            }

            // Ferme le popup
            const alpineElement = document.querySelector('[x-data="{ productDetail: null }"]');
            if (alpineElement && alpineElement.__x) {
                alpineElement.__x.$data.productDetail = null;
            }
        });
    });
</script>

@livewireScripts
</body>
</html>
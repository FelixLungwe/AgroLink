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
<section class="relative bg-gradient-to-br from-green-600 to-emerald-700 text-white py-32">...</section>

{{-- Produits en vedette --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <h3 class="text-4xl md:text-5xl font-bold text-center mb-16">Les produits frais du moment</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" x-data>
            @foreach([
                ['id' => 1, 'nom' => 'Tomates anciennes', 'prix' => '3,20 €/kg', 'ferme' => 'Ferme du Soleil - 12 km', 'frais' => 'Récolté ce matin'],
                ['id' => 2, 'nom' => 'Pommes Gala bio', 'prix' => '2,80 €/kg', 'ferme' => 'Verger Martin - 8 km', 'frais' => 'Récolté hier'],
                ['id' => 3, 'nom' => 'Œufs plein air', 'prix' => '3,90 € les 12', 'ferme' => 'Ferme des Poules Heureuses - 5 km', 'frais' => 'Ponte du jour'],
                ['id' => 4, 'nom' => 'Miel de lavande', 'prix' => '11,00 € le pot 500g', 'ferme' => 'Rucher Provençal - 18 km', 'frais' => 'Récolte 2025'],
            ] as $produit)
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-3 cursor-pointer group"
                     @click="$dispatch('open-product-detail', @json($produit))">
                    <div class="h-56 bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center relative overflow-hidden">
                        <i class="ri-fruit-plant-line text-8xl text-green-600 opacity-40 group-hover:opacity-60 transition"></i>
                        <div class="absolute top-4 right-4 bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full font-bold">
                            {{ $produit['frais'] }}
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $produit['nom'] }}</h4>
                        <p class="text-gray-600 text-sm mb-4">{{ $produit['ferme'] }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-green-600">{{ $produit['prix'] }}</span>
                            <button @click.stop="$dispatch('add-to-cart', { id: {{ $produit['id'] }}, name: '{{ $produit['nom'] }}', price: '{{ $produit['prix'] }}' })"
                                    class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-full transition shadow-lg">
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
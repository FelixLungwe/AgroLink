<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroLink - Catalogue de Produits</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-green-600">AgroLink</a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('home') }}" class="border-green-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Accueil
                        </a>
                        <a href="{{ route('catalog.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Catalogue
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Mon Compte
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="ml-4 bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                            Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-green-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl">
                <span class="block">Découvrez nos produits frais</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-green-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Achetez directement auprès des producteurs locaux et profitez de produits frais et de saison.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filter -->
        <div class="mb-8">
            <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Rechercher</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-search-line text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md h-10" 
                               placeholder="Rechercher un produit...">
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center">
                        <input id="bio" name="bio" type="checkbox" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                               {{ request('bio') ? 'checked' : '' }}>
                        <label for="bio" class="ml-2 block text-sm text-gray-700">
                            Bio uniquement
                        </label>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="ri-filter-line mr-2"></i>Filtrer
                </button>
            </form>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="relative pb-2/3 h-48 bg-gray-100">
                            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" class="absolute h-full w-full object-cover">
                            @if($product->bio)
                                <span class="absolute top-2 right-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                                    Bio
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $product->description }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-lg font-bold text-green-600">{{ number_format($product->price_kg, 2) }} € / {{ $product->unit }}</span>
                                @if($product->stock > 0)
                                    <span class="text-sm text-gray-500">{{ $product->stock }} en stock</span>
                                @else
                                    <span class="text-sm text-red-500">Rupture de stock</span>
                                @endif
                            </div>
                            <div class="mt-4">
                                <button 
                                    @if($product->stock > 0)
                                        wire:click="addToCart({{ $product->id }})"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    @else
                                        disabled
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed"
                                    @endif>
                                    <i class="ri-shopping-cart-line mr-2"></i>
                                    {{ $product->stock > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-emotion-sad-line text-5xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Aucun produit trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">Essayez d'ajuster vos critères de recherche ou de filtrage.</p>
                <div class="mt-6">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="ri-arrow-go-back-line mr-2"></i>Réinitialiser les filtres
                    </a>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">AgroLink</h3>
                    <p class="text-gray-400">Connectons les producteurs locaux aux consommateurs pour une alimentation plus saine et durable.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Accueil</a></li>
                        <li><a href="{{ route('catalog.index') }}" class="text-gray-400 hover:text-white">Catalogue</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">À propos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="ri-map-pin-line mr-2"></i> 123 Rue des Fermiers, 75000 Paris
                        </li>
                        <li class="flex items-center">
                            <i class="ri-mail-line mr-2"></i> contact@agrolink.fr
                        </li>
                        <li class="flex items-center">
                            <i class="ri-phone-line mr-2"></i> +33 1 23 45 67 89
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} AgroLink. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>

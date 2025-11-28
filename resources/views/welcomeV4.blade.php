{{-- resources/views/livewire/homepage.blade.php --}}
{{-- ou remplacez votre welcome.blade.php actuel --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fermes Directes - Produits frais du producteur au consommateur</title>
    <meta name="description" content="Achetez directement aux agriculteurs près de chez vous : fruits, légumes, œufs, miel... frais et locaux !">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">

{{-- Header / Navigation --}}
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-green-700">Fermes Directes</h1>
            </div>

            <nav class="hidden md:flex space-x-8">
                <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Accueil</a>
                <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Producteurs</a>
                <a href="#" class="text-gray-700 hover:text-green-600 font-medium">Panier</a>
            </nav>

            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-green-600 font-semibold">
                        Mon compte
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-full hover:bg-green-700 transition">
                        S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- Hero Section --}}
<section class="relative bg-gradient-to-r from-green-600 to-emerald-700 text-white py-24">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-5xl md:text-6xl font-bold mb-6">
            Du producteur<br>directement dans votre assiette
        </h2>
        <p class="text-xl md:text-2xl mb-10 opacity-90">
            Fruits et légumes ultra-frais • Prix justes • Zéro intermédiaire
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#catalogue" class="bg-white text-green-700 px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-100 transition shadow-lg">
                Voir les produits disponibles
            </a>
            <a href="#producteurs" class="border-2 border-white px-8 py-4 rounded-full text-lg font-bold hover:bg-white hover:text-green-700 transition">
                Trouver un producteur près de chez moi
            </a>
        </div>
    </div>
</section>

{{-- Barre de recherche rapide --}}
<div id="catalogue" class="bg-white shadow-md -mt-8 relative z-10 max-w-4xl mx-auto rounded-2xl p-6 -mb-12">
    <div class="flex flex-col md:flex-row gap-4">
        <input type="text" placeholder="Ex : tomates, pommes, œufs bio..."
               class="flex-1 px-6 py-4 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
        <input type="text" placeholder="Votre ville ou code postal"
               class="flex-1 px-6 py-4 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 text-lg">
        <button class="bg-green-600 text-white px-10 py-4 rounded-xl font-bold hover:bg-green-700 transition text-lg">
            Rechercher
        </button>
    </div>
</div>

{{-- Produits en vedette --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <h3 class="text-4xl font-bold text-center text-gray-800 mb-12">
            Produits frais du moment
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
                ['nom' => 'Tomates anciennes', 'prix' => '3,20 €/kg', 'ferme' => 'Ferme du Soleil - 12 km', 'img' => 'tomates', 'frais' => 'Récolté ce matin'],
                ['nom' => 'Pommes Gala bio', 'prix' => '2,80 €/kg', 'ferme' => 'Verger Martin - 8 km', 'img' => 'pommes', 'frais' => 'Récolté hier'],
                ['nom' => 'Œufs plein air', 'prix' => '3,90 € les 12', 'ferme' => 'Ferme des Poules Heureuses - 5 km', 'img' => 'oeufs', 'frais' => 'Ponte du jour'],
                ['nom' => 'Miel de lavande', 'prix' => '11,00 € le pot 500g', 'ferme' => 'Rucher Provençal - 18 km', 'img' => 'miel', 'frais' => 'Récolte 2025'],
            ] as $produit)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="h-48 bg-gray-200 border-2 border-dashed rounded-t-2xl flex items-center justify-center text-gray-400">
                        <i class="ri-image-line text-6xl"></i>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-xl font-bold text-gray-800">{{ $produit['nom'] }}</h4>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">{{ $produit['frais'] }}</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-3">{{ $produit['ferme'] }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-green-600">{{ $produit['prix'] }}</span>
                            <button class="bg-green-600 text-white p-3 rounded-full hover:bg-green-700 transition">
                                <i class="ri-shopping-cart-2-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="#" class="text-green-600 font-bold text-xl hover:underline">
                Voir tous les produits →
            </a>
        </div>
    </div>
</section>

{{-- Avantages --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h3 class="text-4xl font-bold mb-16">Pourquoi acheter chez nous ?</h3>
        <div class="grid md:grid-cols-3 gap-12">
            <div>
                <i class="ri-leaf-line text-6xl text-green-600 mb-4"></i>
                <h4 class="text-2xl font-bold mb-3">100 % local & de saison</h4>
                <p class="text-gray-600">Moins de 30 km en moyenne entre la ferme et votre assiette</p>
            </div>
            <div>
                <i class="ri-hand-coin-line text-6xl text-green-600 mb-4"></i>
                <h4 class="text-2xl font-bold mb-3">Prix juste</h4>
                <p class="text-gray-600">Le producteur fixe son prix. Pas de marge intermédiaire</p>
            </div>
            <div>
                <i class="ri-truck-line text-6xl text-green-600 mb-4"></i>
                <h4 class="text-2xl font-bold mb-3">Retrait ou livraison rapide</h4>
                <p class="text-gray-600">Retrait gratuit à la ferme ou livraison en 24/48h</p>
            </div>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Fermes Directes</h2>
        <p class="mb-8">La plateforme qui reconnecte les agriculteurs et les consommateurs</p>
        <p class="text-gray-400">© 2025 Fermes Directes - Projet académique / TP</p>
    </div>
</footer>
</body>
</html>
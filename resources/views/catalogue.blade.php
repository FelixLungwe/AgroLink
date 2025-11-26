<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AgroLink – Catalogue de Produits</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <header class="bg-green-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">AgroLink</h1>
            <nav class="space-x-6">
                <a href="index.html" class="hover:text-yellow-300 transition">Accueil</a>
                <a href="#catalogue" class="hover:text-yellow-300 transition">Catalogue</a>
                <a href="#contact" class="hover:text-yellow-300 transition">Contact</a>
            </nav>
        </div>
    </header>

    <!-- HERO CATALOGUE -->
    <section class="bg-green-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold">Découvrez Nos Produits Frais</h2>
            <p class="mt-4 text-lg md:text-xl opacity-90">Achetez directement chez le producteur, avec suivi en temps réel grâce à l’IoT.</p>
        </div>
    </section>

    <!-- CATALOGUE GRID -->
    <section id="catalogue" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid gap-10 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">

                <!-- Product Card -->
                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?q=80&w=600&auto=format&fit=crop" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Tomates Bio</h3>
                        <p class="text-gray-500 mb-4">Stock mis à jour automatiquement via IoT.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-semibold">$3.50 / kg</span>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=600&auto=format&fit=crop" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Pommes Fraîches</h3>
                        <p class="text-gray-500 mb-4">Directement du producteur local.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-semibold">$2.80 / kg</span>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1587049352840-5d58a67a47c6?q=80&w=600&auto=format&fit=crop" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Légumes Variés</h3>
                        <p class="text-gray-500 mb-4">Traçabilité complète et frais garantis.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-semibold">$4.00 / kg</span>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?q=80&w=600&auto=format&fit=crop" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Carottes Bio</h3>
                        <p class="text-gray-500 mb-4">Fraîcheur garantie avec mise à jour IoT.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-semibold">$2.00 / kg</span>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-green-800 text-white py-10">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-lg">AgroLink © 2025 – IoT + E-commerce Agricole</p>
            <p class="opacity-80 mt-2">Connecter les producteurs aux consommateurs, en toute transparence.</p>
        </div>
    </footer>

</body>
</html>
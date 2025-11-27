<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fermes Directes - Producteur</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-green-700 to-green-900 text-white">
        <div class="p-6">
            <h1 class="text-2xl font-bold flex items-center gap-3">
                <i class="ri-leaf-line text-3xl"></i> Fermes Directes
            </h1>
            <p class="text-green-200 text-sm mt-1">Espace Producteur</p>
        </div>

        <nav class="mt-8">
            <a href="{{ route('producer.dashboard') }}"
               class="flex items-center gap-3 px-6 py-4 hover:bg-green-800 transition {{ request()->routeIs('producer.dashboard') ? 'bg-green-800 border-l-4 border-white' : '' }}">
                <i class="ri-dashboard-line text-xl"></i>
                Tableau de bord
            </a>
            <a href="{{ route('producer.products') }}"
               class="flex items-center gap-3 px-6 py-4 hover:bg-green-800 transition {{ request()->routeIs('producer.products') ? 'bg-green-800 border-l-4 border-white' : '' }}">
                <i class="ri-boxing-line text-xl"></i>
                Mes produits
            </a>
            <a href="{{ route('producer.orders') }}"
               class="flex items-center gap-3 px-6 py-4 hover:bg-green-800 transition {{ request()->routeIs('producer.orders') ? 'bg-green-800 border-l-4 border-white' : '' }}">
                <i class="ri-shopping-bag-3-line text-xl"></i>
                Commandes
            </a>
            <a href="#" class="flex items-center gap-3 px-6 py-4 hover:bg-green-800 transition">
                <i class="ri-bar-chart-line text-xl"></i>
                Statistiques
            </a>
        </nav>

        <div class="absolute bottom-0 w-64 p-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-green-200 text-xs">Producteur</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contenu principal -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Tableau de bord' }}</h2>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">Stock IoT : <span class="text-green-600 font-bold">Connecté</span></span>
                <a href="{{ route('logout') }}" class="text-red-600 hover:text-red-700">
                    <i class="ri-logout-box-line text-2xl"></i>
                </a>
            </div>
        </header>

        <!-- Contenu dynamique -->
        <main class="flex-1 p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
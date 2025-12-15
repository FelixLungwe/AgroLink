<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paiement réussi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <div class="text-green-500 text-6xl mb-4">
                        <i class="ri-checkbox-circle-fill"></i>
                    </div>
                    <h2 class="text-2xl font-semibold mb-4">Merci pour votre achat !</h2>
                    <p class="text-gray-600 mb-6">Votre commande a été passée avec succès.</p>
                    <div class="space-x-4">
                        <a href="{{ route('home') }}" 
                           class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                            Retour à l'accueil
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="bg-white text-indigo-600 border border-indigo-600 px-6 py-2 rounded-lg hover:bg-gray-50">
                            Voir mes commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
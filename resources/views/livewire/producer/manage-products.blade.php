<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-6">

        <!-- En-tête -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Gestion de mes produits</h1>
                <p class="text-gray-600 mt-2">Ajoutez, modifiez ou supprimez vos produits en vente</p>
            </div>
            <button wire:click="create"
                    class="bg-green-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition flex items-center gap-2">
                <i class="ri-add-line text-xl"></i> Nouveau produit
            </button>
        </div>

        <!-- Barre de recherche -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Rechercher un produit..."
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <!-- Formulaire création/édition -->
        @if($isCreating || $editId)
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border-2 border-green-200">
            <h2 class="text-2xl font-bold mb-6">{{ $editId ? 'Modifier' : 'Ajouter' }} un produit</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Nom du produit</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Prix ({{ $unit === 'kg' ? '€/kg' : '€/unité' }})</label>
                    <input type="number" step="0.01" wire:model="price_kg" class="w-full px-4 py-3 border rounded-lg">
                    @error('price_kg') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Unité de vente</label>
                    <select wire:model="unit" class="w-full px-4 py-3 border rounded-lg">
                        <option value="kg">Kilogramme</option>
                        <option value="piece">Pièce</option>
                        <option value="botte">Botte</option>
                        <option value="barquette">Barquette</option>
                        <option value="pot">Pot</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Stock actuel</label>
                    <input type="number" wire:model="stock" class="w-full px-4 py-3 border rounded-lg">
                    <small class="text-green-600 flex items-center gap-1 mt-1">
                        <i class="ri-sensor-fill animate-pulse"></i> Mise à jour automatique IoT active
                    </small>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-3 border rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Photo</label>
                    <input type="file" wire:model="photo" accept="image/*" class="w-full">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" wire:model="bio" id="bio" class="w-5 h-5 text-green-600">
                    <label for="bio" class="ml-3 text-gray-700 font-medium">Produit certifié Bio</label>
                </div>
            </div>

            <div class="flex gap-4 mt-8">
                <button wire:click="{{ $editId ? 'update' : 'store' }}"
                        class="bg-green-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-700 transition">
                    {{ $editId ? 'Mettre à jour' : 'Publier le produit' }}
                </button>
                <button wire:click="cancel" class="bg-gray-500 text-white px-8 py-3 rounded-xl hover:bg-gray-600">
                    Annuler
                </button>
            </div>
        </div>
        @endif

        <!-- Liste des produits -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                        @if($product->photo)
                            <img src="{{ Storage::url($product->photo) }}" class="h-full w-full object-cover">
                        @else
                            <i class="ri-image-2-line text-8xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-800">{{ $product->name }}</h3>
                            @if($product->bio)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">BIO</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-green-600">
                                {{ number_format($product->price_kg, 2) }} €/{{ $product->unit }}
                            </span>
                            <span class="text-lg font-semibold {{ $product->stock > 10 ? 'text-green-600' : 'text-red-600' }}">
                                Stock : {{ $product->stock }} {{ $product->unit }}
                            </span>
                        </div>

                        <div class="flex gap-3">
                            <button wire:click="edit({{ $product->id }})"
                                    class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                <i class="ri-pencil-line"></i> Modifier
                            </button>
                            <button wire:click="delete({{ $product->id }})" wire:confirm="Supprimer ce produit ?"
                                    class="bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</div>

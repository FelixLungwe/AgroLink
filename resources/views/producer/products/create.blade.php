<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un nouveau produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('producer.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Colonne de gauche -->
                            <div class="space-y-6">
                                <!-- Nom du produit -->
                                <div>
                                    <x-label for="name" :value="__('Nom du produit')" />
                                    <x-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <x-label for="description" :value="__('Description')" />
                                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Prix et Unité -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="price" :value="__('Prix (€)')" />
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">€</span>
                                            </div>
                                            <input type="number" step="0.01" min="0" name="price" id="price" 
                                                   class="focus:ring-green-500 focus:border-green-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                                   placeholder="0.00" value="{{ old('price') }}" required>
                                        </div>
                                        @error('price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-label for="unit" :value="__('Unité')" />
                                        <select id="unit" name="unit" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                            <option value="kg" {{ old('unit', 'kg') === 'kg' ? 'selected' : '' }}>kg</option>
                                            <option value="pièce" {{ old('unit') === 'pièce' ? 'selected' : '' }}>Pièce</option>
                                            <option value="botte" {{ old('unit') === 'botte' ? 'selected' : '' }}>Botte</option>
                                            <option value="barquette" {{ old('unit') === 'barquette' ? 'selected' : '' }}>Barquette</option>
                                            <option value="pot" {{ old('unit') === 'pot' ? 'selected' : '' }}>Pot</option>
                                        </select>
                                        @error('unit')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Prix de comparaison -->
                                <div>
                                    <x-label for="compare_at_price" :value="__('Prix barré (optionnel)')" />
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">€</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" name="compare_at_price" id="compare_at_price" 
                                               class="focus:ring-green-500 focus:border-green-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                               placeholder="0.00" value="{{ old('compare_at_price') }}">
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Affiche une réduction par rapport au prix normal</p>
                                    @error('compare_at_price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Colonne de droite -->
                            <div class="space-y-6">
                                <!-- Image du produit -->
                                <div>
                                    <x-label for="image" :value="__('Image du produit')" />
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                    <span>Télécharger une image</span>
                                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="pl-1">ou glisser-déposer</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF jusqu'à 2MB
                                            </p>
                                        </div>
                                    </div>
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2" id="image-preview"></div>
                                </div>

                                <!-- Catégorie -->
                                <div>
                                <x-label for="category_id" :value="__('Catégorie')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <!-- Deuxième ligne -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                            <!-- Stock -->
                            <div>
                                <x-label for="stock" :value="__('Stock disponible')" />
                                <x-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" :value="old('stock', 0)" required />
                                <p class="mt-1 text-sm text-gray-500">Quantité disponible à la vente</p>
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Seuil d'alerte stock -->
                            <div>
                                <x-label for="stock_threshold" :value="__('Seuil d\'alerte stock')" />
                                <x-input id="stock_threshold" name="stock_threshold" type="number" min="0" class="mt-1 block w-full" :value="old('stock_threshold', 5)" />
                                <p class="mt-1 text-sm text-gray-500">Alerte quand le stock est inférieur à cette valeur</p>
                                @error('stock_threshold')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date de récolte -->
                            <div>
                                <x-label for="harvested_at" :value="__('Date de récolte')" />
                                <x-input id="harvested_at" name="harvested_at" type="date" class="mt-1 block w-full" :value="old('harvested_at')" />
                                <p class="mt-1 text-sm text-gray-500">Date à laquelle le produit a été récolté</p>
                                @error('harvested_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="pt-6">
                            <fieldset>
                                <legend class="text-base font-medium text-gray-900">Options du produit</legend>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_available" name="is_available" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('is_available', true) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_available" class="font-medium text-gray-700">Disponible à la vente</label>
                                            <p class="text-gray-500">Le produit sera visible par les clients</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_featured" name="is_featured" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('is_featured') ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_featured" class="font-medium text-gray-700">Mettre en avant</label>
                                            <p class="text-gray-500">Afficher en tête de liste</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_bestseller" name="is_bestseller" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('is_bestseller') ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_bestseller" class="font-medium text-gray-700">Meilleure vente</label>
                                            <p class="text-gray-500">Afficher dans la section des meilleures ventes</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_new" name="is_new" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('is_new', true) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_new" class="font-medium text-gray-700">Nouveau produit</label>
                                            <p class="text-gray-500">Afficher la mention "Nouveau"</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="bio" name="bio" type="checkbox" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded" {{ old('bio') ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="bio" class="font-medium text-gray-700">Produit bio</label>
                                            <p class="text-gray-500">Cocher si le produit est issu de l'agriculture biologique</p>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="pt-6 flex justify-end space-x-4">
                            <a href="{{ route('producer.products') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Enregistrer le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Aperçu de l'image
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'h-32 w-32 object-cover rounded-md';
                    
                    const previewContainer = document.getElementById('image-preview');
                    previewContainer.innerHTML = '';
                    previewContainer.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });

        // Validation du prix de comparaison
        document.getElementById('compare_at_price').addEventListener('change', function() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const comparePrice = parseFloat(this.value) || 0;
            
            if (comparePrice > 0 && comparePrice <= price) {
                alert('Le prix barré doit être supérieur au prix normal');
                this.value = '';
            }
        });
    </script>
    @endpush
</x-app-layout>
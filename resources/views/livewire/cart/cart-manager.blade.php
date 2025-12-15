<div>
    {{-- Panier flottant --}}
    <div x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }" class="fixed bottom-6 right-6 z-50">
        <!-- Bouton panier -->
        <button @click="open = !open" 
                class="bg-green-600 hover:bg-green-700 text-white rounded-full p-5 shadow-2xl transition transform hover:scale-110 relative">
            <i class="ri-shopping-cart-2-line text-2xl"></i>
            @if($totalItems > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
                    {{ $totalItems }}
                </span>
            @endif
        </button>

        <!-- Contenu du panier -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 transform scale-90"
             @click.away="open = false"
             class="absolute bottom-20 right-0 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             style="max-height: 80vh;">
             
            <div class="bg-green-600 text-white p-4 flex justify-between items-center">
                <h3 class="font-bold text-lg">Votre panier</h3>
                <div class="flex items-center space-x-4">
                    @if(!$cartItems->isEmpty())
                        <button wire:click="clearCart" 
                                class="text-white hover:text-red-200 text-sm flex items-center"
                                title="Vider le panier">
                            <i class="ri-delete-bin-line mr-1"></i> Vider
                        </button>
                    @endif
                    <button @click="open = false" class="text-white hover:text-gray-200">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                @if($cartItems->isEmpty())
                    <div class="text-center py-8">
                        <i class="ri-shopping-cart-line text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Votre panier est vide</p>
                        <a href="{{ route('catalog.index') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="ri-store-2-line mr-2"></i> Voir le catalogue
                        </a>
                    </div>
                @else
                    @foreach($cartItems as $item)
                        <div class="flex items-start gap-4 py-4 border-b last:border-b-0">
                            <div class="flex-shrink-0 w-16 h-16 bg-white rounded-lg overflow-hidden border">
                                <img src="{{ $item['photo'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $item['name'] }}</h4>
                                <p class="text-green-600 font-bold">
                                    {{ number_format($item['price'], 2, ',', ' ') }} € / {{ $item['unit'] ?? 'unité' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Stock: {{ $item['max_quantity'] }} disponible(s)
                                </p>
                            </div>
                            <div class="flex flex-col items-end">
                                <div class="flex items-center gap-2">
                                    <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                            class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                                        <i class="ri-subtract-line"></i>
                                    </button>
                                    <input type="number" 
                                           value="{{ $item['quantity'] }}"
                                           min="1" 
                                           max="{{ $item['max_quantity'] }}"
                                           wire:change="$set('quantity', $event.target.value)"
                                           wire:keydown.enter="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                           class="w-12 text-center border rounded-md py-1 text-sm"
                                           onfocus="this.select()">
                                    <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                            @if($item['quantity'] >= $item['max_quantity']) disabled @endif
                                            class="w-7 h-7 rounded-full bg-green-600 text-white hover:bg-green-700 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>
                                <button wire:click="removeItem({{ $item['id'] }})" 
                                        class="mt-2 text-red-500 hover:text-red-700 text-sm flex items-center">
                                    <i class="ri-delete-bin-line mr-1"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if(!$cartItems->isEmpty())
                <div class="border-t bg-gray-50 p-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold">Sous-total</span>
                        <span class="font-bold text-green-600">{{ number_format($totalAmount, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="flex justify-between items-center mb-4 text-sm text-gray-500">
                        <span>Livraison</span>
                        <span>Calculée à l'étape suivante</span>
                    </div>
                    <div class="border-t border-dashed my-3"></div>
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold">Total TTC</span>
                        <span class="text-xl font-bold text-green-600">{{ number_format($totalAmount, 2, ',', ' ') }} €</span>
                    </div>
                    
                    <button wire:click="checkout" 
                            wire:loading.attr="disabled"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-bold transition shadow-md flex items-center justify-center">
                        <span wire:loading.remove wire:target="checkout">
                            <i class="ri-checkbox-circle-line mr-2"></i> Valider la commande
                        </span>
                        <span wire:loading wire:target="checkout" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Traitement...
                        </span>
                    </button>
                    
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        Paiement sécurisé avec <i class="ri-shield-check-line text-green-500"></i> Stripe
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notifications -->
    <div x-data="{ show: false, type: 'success', message: '' }" 
         x-init="
            window.livewire.on('notify', data => {
                show = true;
                type = data.type;
                message = data.message;
                setTimeout(() => { show = false }, 5000);
            })
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         class="fixed top-4 right-4 z-50 w-80">
        <div x-bind:class="{
            'bg-green-500': type === 'success',
            'bg-red-500': type === 'error',
            'bg-yellow-500': type === 'warning',
            'bg-blue-500': type === 'info'
        }" 
        class="text-white rounded-lg shadow-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <template x-if="type === 'success'">
                        <i class="ri-checkbox-circle-fill text-xl"></i>
                    </template>
                    <template x-if="type === 'error'">
                        <i class="ri-error-warning-fill text-xl"></i>
                    </template>
                    <template x-if="type === 'warning'">
                        <i class="ri-alert-fill text-xl"></i>
                    </template>
                    <template x-if="type === 'info'">
                        <i class="ri-information-fill text-xl"></i>
                    </template>
                </div>
                <div class="ml-3 flex-1">
                    <p x-text="message" class="text-sm"></p>
                </div>
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fermer le panier après une redirection (comme après l'ajout au panier)
    document.addEventListener('livewire:load', function () {
        if (window.location.hash === '#cart') {
            Livewire.emit('openCart');
        }
    });
</script>
@endpush

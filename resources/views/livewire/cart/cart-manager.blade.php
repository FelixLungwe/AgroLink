<div>
    {{-- Panier flottant --}}
    <div x-data="{ open: false }" class="fixed bottom-6 right-6 z-50">
        <!-- Bouton panier -->
        <button @click="open = !open" 
                class="bg-green-600 hover:bg-green-700 text-white rounded-full p-5 shadow-2xl transition transform hover:scale-110 relative">
            <i class="ri-shopping-cart-2-line text-2xl"></i>
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
                {{ $totalItems }}
            </span>
        </button>

        <!-- Contenu du panier -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="absolute bottom-20 right-0 w-96 bg-white rounded-2xl shadow-2xl overflow-hidden">
             
            <div class="bg-green-600 text-white p-4 flex justify-between items-center">
                <h3 class="font-bold text-lg">Votre panier</h3>
                <button @click="open = false" class="text-white hover:text-gray-200">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="max-h-96 overflow-y-auto p-4">
                @if($cart->isEmpty())
                    <p class="text-center text-gray-500 py-8">Votre panier est vide</p>
                @else
                    @foreach($cart as $id => $item)
                        <div class="flex items-center gap-4 py-3 border-b">
                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex-shrink-0"></div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm">{{ $item['name'] }}</h4>
                                <p class="text-green-600 font-bold">{{ $item['price'] }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})"
                                        class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <span class="w-10 text-center font-bold">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})"
                                        class="w-8 h-8 rounded-full bg-green-600 text-white hover:bg-green-700 flex items-center justify-center">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                            <button wire:click="removeItem({{ $id }})" class="text-red-500 hover:text-red-700 ml-2">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>

            @if(!$cart->isEmpty())
                <div class="p-4 bg-gray-50 border-t">
                    <div class="flex justify-between font-bold text-lg mb-4">
                        <span>Total</span>
                        <span class="text-green-600">{{ $totalPrice }} €</span>
                    </div>
                    <!-- <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold transition">
                        Valider le panier
                    </button> -->
                    <button wire:click="checkout" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-bold text-lg transition shadow-lg">
                        Payer avec Stripe
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paiement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-6">Récapitulatif de la commande</h2>
                    
                    <div class="space-y-4 mb-8">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center p-4 border rounded-lg">
                                <div class="flex items-center space-x-4">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                    @endif
                                    <div>
                                        <h3 class="font-medium">{{ $item['name'] }}</h3>
                                        <p class="text-gray-600">{{ $item['quantity'] }} x {{ number_format($item['price'], 2, ',', ' ') }} €</p>
                                    </div>
                                </div>
                                <div class="font-semibold">
                                    {{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-semibold">Total</h3>
                            <span class="text-2xl font-bold">
                                {{ number_format(array_reduce($cart, function($carry, $item) {
                                    return $carry + ($item['price'] * $item['quantity']);
                                }, 0), 2, ',', ' ') }} €
                            </span>
                        </div>

                        <form action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <!-- Ici, vous ajouterez les champs de paiement (carte de crédit, etc.) -->
                            <div class="mb-6">
                                <h4 class="font-medium mb-4">Informations de paiement</h4>
                                <!-- Exemple avec Stripe Elements -->
                                <div id="card-element" class="p-4 border rounded-lg mb-4">
                                    <!-- Les champs de carte seront injectés ici par JavaScript -->
                                </div>
                                <div id="card-errors" role="alert" class="text-red-600 text-sm mt-2"></div>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg hover:bg-indigo-700 transition">
                                Payer maintenant
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Configuration de Stripe
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        // Gestion des erreurs de carte
        const cardErrors = document.getElementById('card-errors');
        cardElement.on('change', function(event) {
            if (event.error) {
                cardErrors.textContent = event.error.message;
            } else {
                cardErrors.textContent = '';
            }
        });

        // Soumission du formulaire
        const form = document.querySelector('form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            const {error, paymentMethod} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                cardErrors.textContent = error.message;
            } else {
                // Ajouter le payment_method_id au formulaire et le soumettre
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method_id');
                hiddenInput.setAttribute('value', paymentMethod.id);
                form.appendChild(hiddenInput);
                
                form.submit();
            }
        });
    </script>
    @endpush
</x-app-layout>
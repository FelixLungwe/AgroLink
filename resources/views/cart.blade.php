<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Votre panier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(empty($cart))
                        <p>Votre panier est vide</p>
                        <a href="{{ route('catalog.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ __('Continuer vos achats') }}
                        </a>
                    @else
                        <div class="space-y-6">
                            @foreach($cart as $item)
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded">
                                        @endif
                                        <div>
                                            <h3 class="font-medium">{{ $item['name'] }}</h3>
                                            <p class="text-gray-600">{{ number_format($item['price'], 2, ',', ' ') }} €</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   class="w-16 text-center border rounded"
                                                   onchange="this.form.submit()">
                                        </form>
                                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            <div class="flex justify-between items-center pt-4 border-t">
                                <h3 class="text-lg font-semibold">Total: {{ number_format($total, 2, ',', ' ') }} €</h3>
                                <a href="{{ route('payment.checkout') }}" 
                                   class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                    Passer la commande
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
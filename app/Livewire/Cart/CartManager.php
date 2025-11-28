<?php

namespace App\Livewire\Cart;

use Livewire\Component;

class CartManager extends Component
{
    public $cart = [];
    public $totalItems = 0;
    public $totalPrice = '0,00';

    // Remplace cette ligne :
// protected $listeners = ['addToCart' => 'addItem'];

// Par celle-ci (elle écoute les événements globaux Livewire) :
protected $listeners = ['addToCart'];

public function addToCart($id, $name, $price)
{
    $this->addItem($id, $name, $price);
}

    public function mount()
    {
        // Données par défaut pour démo
        $this->cart = collect([
            1 => ['name' => 'Tomates anciennes', 'price' => '3,20 €/kg', 'quantity' => 2],
            3 => ['name' => 'Œufs plein air', 'price' => '3,90 € les 12', 'quantity' => 1],
        ]);
        $this->calculateTotals();
    }

    public function addItem($productId, $name, $price)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'name' => $name,
                'price' => $price,
                'quantity' => 1
            ];
        }
        $this->calculateTotals();
    }

    public function updateQuantity2($id, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$id]);
        } else {
            $this->cart[$id]['quantity'] = $quantity;
        }
        $this->calculateTotals();
    }

    // app/Livewire/CartManager.php

    public function updateQuantity($id, $quantity)
    {
        if ($quantity <= 0) {
            unset($this->cart[$id]); // Corrigé : bien supprimé
        } else {
            $this->cart[$id]['quantity'] = $quantity;
        }
        $this->calculateTotals();
    }

    public function checkout()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $line_items = [];
        foreach ($this->cart as $item) {
            $price = floatval(str_replace(',', '.', str_replace([' €/kg', ' € les 12', ' € le pot 500g'], '', $item['price'])));
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $item['name']],
                    'unit_amount' => $price * 100, // en centimes
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('home') . '?success=1',
            'cancel_url' => route('home') . '?canceled=1',
        ]);

        return redirect($session->url);
    }

    public function removeItem($id)
    {
        unset($this->cart[$id]);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->totalItems = collect($this->cart)->sum('quantity');

        // Conversion simple du prix (ex: "3,20 €/kg" → 3.20)
        $total = collect($this->cart)->sum(function ($item) {
            $price = str_replace([' €/kg', ' € les 12', ' € le pot 500g'], '', $item['price']);
            $price = str_replace(',', '.', $price);
            return floatval($price) * $item['quantity'];
        });

        $this->totalPrice = number_format($total, 2, ',', '');
    }

    public function render()
    {
        return view('livewire.cart.cart-manager');
    }
}
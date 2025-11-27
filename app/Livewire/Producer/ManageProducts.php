<?php

namespace App\Livewire\Producer;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageProducts extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isCreating = false;
    public $editId = null;

    // Champs formulaire
    public $name, $description, $price_kg, $unit = 'kg', $stock = 0, $bio = false, $photo;

    protected $rules = [
        'name'        => 'required|string|max:255',
        'description' => 'required|string',
        'price_kg'    => 'required|numeric|min:0',
        'unit'        => 'required|in:kg,piece,botte,barquette,pot',
        'stock'       => 'required|numeric|min:0',
        'bio'         => 'boolean',
        'photo'       => 'nullable|image|max:2048',
    ];

    public function render()
    {
        $products = Product::where('producer_id', auth()->id())
            ->where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.product.manage-products', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function store()
    {
        $this->validate();

        $product = Product::create([
            'producer_id'  => auth()->id(),
            'name'         => $this->name,
            'description'  => $this->description,
            'price_kg'     => $this->price_kg,
            'unit'         => $this->unit,
            'stock'        => $this->stock,
            'bio'          => $this->bio,
            'available'    => true,
        ]);

        if ($this->photo) {
            $product->update(['photo' => $this->photo->store('products', 'public')]);
        }

        $this->isCreating = false;
        $this->dispatch('toast', 'Produit ajouté avec succès !');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->editId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price_kg = $product->price_kg;
        $this->unit = $product->unit;
        $this->stock = $product->stock;
        $this->bio = $product->bio;
    }

    public function update()
    {
        $this->validate();

        $product = Product::findOrFail($this->editId);
        $product->update([
            'name'        => $this->name,
            'description' => $this->description,
            'price_kg'    => $this->price_kg,
            'unit'        => $this->unit,
            'stock'       => $this->stock,
            'bio'         => $this->bio,
        ]);

        if ($this->photo) {
            $product->update(['photo' => $this->photo->store('products', 'public')]);
        }

        $this->editId = null;
        $this->dispatch('toast', 'Produit mis à jour !');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        $this->dispatch('toast', 'Produit supprimé');
    }

    public function resetForm()
    {
        $this->reset(['name','description','price_kg','unit','stock','bio','photo','editId']);
    }

    public function cancel()
    {
        $this->isCreating = false;
        $this->editId = null;
        $this->resetForm();
    }
}
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;


class ProductController extends Component
{
    public $products, $name, $price, $description, $mobile, $product_id;
    public $isDialogOpen = 0;

    public function render()
    {
        $this->products = Product::all();
        return view('livewire.product-controller');
    }

    public function create()
    {
        $this->resetCreateForm();
        $this->openModalPopover();
    }

    public function openModalPopover()
    {
        $this->isDialogOpen = true;
    }

    public function closeModalPopover()
    {
        $this->isDialogOpen = false;
    }

    private function resetCreateForm(){
        $this->name = '';
        $this->price = '';
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'price' => 'required',
        ]);
    
        Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ]);

        session()->flash('message', $this->product_id ? 'Product updated!' : 'Product created!');

        $this->closeModalPopover();
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
    
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Product::find($id)->delete();
        session()->flash('message', 'Product removed!');
    }    
}
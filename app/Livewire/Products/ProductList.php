<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;
    public string $search = '';
    public int $perPage = 15;
    public string $orderField = 'created_at';
    public string $orderDirection = 'desc';

    //hook like updating creating  thisrun automatically before search
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }
    public function setOrder(string $field): void
    {
        if ($this->orderField === $field) {
            $this->orderDirection = $this->orderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->orderField = $field;
            $this->orderDirection = 'asc';
        }
        $this->resetPage();
    }
    public function render()
    {
        $products = Product::query()->with('category')
            ->when($this->search, fn($q) =>
            $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->orderField, $this->orderDirection)
            ->paginate($this->perPage);
        return view('livewire.products.product-list', [
            'products' => $products
        ]);
    }
}

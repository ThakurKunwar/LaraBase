<?php

namespace App\Livewire;

use App\Http\Repositories\ProductRepository;
use App\Livewire\PowerGrid\PowerGridComponent;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Override;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'product-table';
    public string $primaryKey = 'id';

    public function __construct()
    {
        $this->repository = new ProductRepository();
    }

    // in ProductTable
    public function datasource(): Builder
    {
        return $this->repository->prepareModel()->with(['category']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('price')
            ->add('category_id')
            ->add('category_name', fn($product) =>
            $product->category?->name)
            ->add(
                'created_at_formatted',
                fn($product) =>
                $product->created_at->format('d M Y')
            );
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Price', 'price')
                ->sortable(),
            Column::make('Category', 'category_name'),
            Column::make('Created at', 'created_at_formatted', 'created_at'),
            Column::action('Action')
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.actions', ['row' => $row, 'repository' => $this->repository]);
    }

    public function actions($row): array
    {

        return [
            $this->editButton($row),
            $this->deleteButton($row),
        ];
    }


    public function filters(): array
    {
        return
            [
                Filter::select('category_name', 'category_id')
                    ->dataSource(Category::all())
                    ->optionLabel('name')
                    ->optionValue('id')

            ];
    }



    /*
    public function actionRules(Product $row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}

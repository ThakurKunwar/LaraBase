<?php

namespace App\Livewire;

use App\Http\Repositories\BrandRepository;
use App\Livewire\PowerGrid\PowerGridComponent;
use Illuminate\Database\Eloquent\Model;
use Override;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

class BrandTable extends PowerGridComponent
{
    public string $tableName = 'brand-table';
    public string $primaryKey = 'id';

    public function __construct()
    {
        $this->repository = new BrandRepository();
    }
    #[Override]
    public function fields(): PowerGridFields
    {
        return parent::fields()
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('country')
            ->add('is_active')

        ;
    }
    #[Override]
    public function columns(): array
    {
        return
            [
                Column::make('Id', 'id'),
                Column::make('Name', 'name')->searchable()->sortable(),
                Column::make('Slug', 'slug'),
                Column::make('Country', 'country')->sortable(),
                Column::make('is_Active', 'is_active'),
                Column::action('Action'),

            ];
    }
    #[Override]
    public function actions(Model $row): array
    {
        return
            [
                $this->editButton($row),
                $this->deleteButton($row),
            ];
    }
    public function filters(): array
    {
        return [
            Filter::boolean('is_active')->label('Active', 'Inactive'),
        ];
    }
}

<?php

namespace App\Livewire;

use App\Http\Repositories\CategoryRepository;
use App\Livewire\PowerGrid\PowerGridComponent;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Override;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'category-table';
    public string $primaryKey = 'id';

    public function __construct()
    {
        $this->repository = new CategoryRepository();
    }
    public function fields(): PowerGridFields
    {

        return PowerGrid::fields()
            ->add('id')
            ->add('name');
    }
    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),
            Column::action('Action')
        ];
    }
    #[Override]
    public function actions(Model $row): array
    {
        return [
            $this->editButton($row),
            $this->deleteButton($row)
        ];
    }
}

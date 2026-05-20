<?php

namespace App\Livewire\PowerGrid;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent as BasePowerGridPowerGridComponent;

class PowerGridComponent extends BasePowerGridPowerGridComponent
{
    protected  $repository;

    public function setUp(): array
    {

        $this->showCheckbox();
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()
                ->showRecordCount(),


        ];
    }

    public function datasource(): Builder
    {
        return $this->repository->prepareModel();
    }

    public function editButton($row, string $field = 'id'): Button
    {
        return Button::add('edit')
            ->slot('Edit')
            ->id()
            ->route(
                $this->repository->modelNames . '.edit',
                [$this->repository->modelKey => $row->{$field}],
                '_self'
            );
    }

    public function deleteButton($row, string $field = 'id'): Button
    {
        return Button::add('delete')
            ->slot('Delete')
            ->id()
            ->class('text-red-600')
            ->dispatch('deleteConfirm', ['rowId' => $row->{$field}]);
    }



    #[On('deleteConfirm')]
    public function deleteConfirm($rowId): void
    {
        try {
            $this->repository->delete($rowId);
        } catch (Exception $e) {

            $this->dispatch('error', message: $e->getMessage());
            return;
        }
    }
    public function actions(Model $row): array
    {
        return [
            $this->editButton($row),
            $this->deleteButton($row),
        ];
    }
}

<?php

namespace App\Livewire\PowerGrid;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent as BasePowerGridPowerGridComponent;

class PowerGridComponent extends BasePowerGridPowerGridComponent
{
    protected $repository;

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

    public function editButton(Model $row): Button
    {
        return Button::add('edit')
            ->slot('Edit')
            ->id()
            ->route(
                $this->repository->modelNames . '.edit',
                [$this->repository->modelKey => $row->id],
                '_self'  // ← add this
            );
    }

    public function deleteButton(Model $row): Button
    {
        return Button::add('delete')
            ->slot('Delete')
            ->id()
            ->class('text-red-600')
            ->dispatch('deleteConfirm', ['rowId' => $row->id]);
    }

    #[\Livewire\Attributes\On('deleteConfirm')]
    public function deleteConfirm($rowId): void
    {
        try {
            $this->repository->delete($rowId);
        } catch (Exception $e) {

            $this->dispatch('error', message: $e->getMessage());
            return;
        }
    }
}

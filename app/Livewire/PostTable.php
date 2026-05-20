<?php

namespace App\Livewire;

use App\Http\Repositories\PostRepository;
use App\Livewire\PowerGrid\PowerGridComponent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Override;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PostTable extends PowerGridComponent
{
    public string $tableName = "post-table";
    public string $primaryKey = 'id';
    public function __construct()
    {
        $this->repository = new PostRepository();
    }
    #[Override]
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('title')
            ->add('slug')
            ->add('body')
            ->add(
                'image',
                fn($post) => $post->media
                    ? Blade::render(
                        '<img src="{{ $url }}" width="50">',
                        ['url' => $post->media->full_url]
                    )
                    : 'No image'
            )
            ->add('is_published')
        ;
    }
    #[Override]
    public function columns(): array
    {
        return
            [
                Column::make('Title', 'title')
                    ->searchable()->sortable(),
                Column::make('Slug', 'slug'),
                Column::make('Body', 'body')->sortable(),
                Column::make('Image', 'image'),
                Column::make('is_Published', 'is_published'),
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
    #[Override]
    public function filters(): array
    {
        return
            [
                Filter::boolean('is_published')->label('active', 'inActive'),
            ];
    }
}

<?php

namespace App\Http\Repositories;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */



    public function __construct(Category $category)
    {
        //
        $this->model = $category;
        parent::__construct();
    }
}

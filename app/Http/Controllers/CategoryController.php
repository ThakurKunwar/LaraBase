<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Requests\CategoryRequest;

class CategoryController extends BaseController
{
    protected $formRequest = CategoryRequest::class;
    //
    public function __construct(CategoryRepository $repo)
    {
        $this->repository = $repo;
        $this->repository->withCache();
        parent::__construct();
    }
}

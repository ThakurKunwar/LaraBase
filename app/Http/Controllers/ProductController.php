<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Requests\ProductRequest;


class ProductController extends BaseController
{

    protected $formRequest = ProductRequest::class;


    public function __construct(ProductRepository $repo)
    {
        $this->repository = $repo;
        $this->repository->withCache();
        parent::__construct();
    }

    public function withCreate(): array
    {
        return [
            'categories' => app(CategoryRepository::class)->withCache()->all()
        ];
    }
}

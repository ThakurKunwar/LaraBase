<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    protected $productRepository;

    public function __construct(ProductRepository $repo)
    {
        $this->repository = $repo;
        $this->repository->withCache();
        parent::__construct();
    }
}

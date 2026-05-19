<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository
{



    public function __construct()
    {
        $this->model = new Product();
        parent::__construct();
    }
}

<?php

namespace App\Http\Repositories;

use App\Models\Brand;

class BrandRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->model = new Brand();
        parent::__construct();
    }
}

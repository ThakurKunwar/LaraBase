<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Repositories\BrandRepository;
use App\Http\Requests\BrandRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BrandController extends BaseController
{
    protected $formRequest = BrandRequest::class;

    public function __construct()
    {
        $this->repository = new BrandRepository();
        parent::__construct();
    }
}

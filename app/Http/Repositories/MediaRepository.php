<?php

namespace App\Http\Repositories;

use App\Models\Media;

class MediaRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
        $this->model = new Media();
        parent::__construct();
    }
}

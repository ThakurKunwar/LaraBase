<?php

namespace App\Http\Repositories;

use App\Models\Post;

class PostRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
        $this->model = new Post();
        parent::__construct();
    }
}

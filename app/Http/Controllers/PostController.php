<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PostRepository;
use App\Http\Requests\PostRequest;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Override;

class PostController extends BaseController
{
    //
    public $formRequest = PostRequest::class;

    public function __construct()
    {
        $this->repository = new PostRepository();
        parent::__construct();
    }
    #[Override]
    public function processStoreData(FormRequest $request)
    {
        return $request->safe()->except(['image']);
    }
    #[Override]
    public function storeCallBack(Model $resource, FormRequest $request)
    {
        if ($request->hasFile('image')) {
            MediaService::uploadMedia(
                $request->file('image'),
                $resource
            );
        }
        return $resource;
    }
    #[Override]
    public function updateCallBack(Model $resource, FormRequest $request)
    {
        if ($request->hasFile('image')) {
            MediaService::uploadMedia(
                $request->file('image'),
                $resource
            );
        }
        return $resource;
    }

    #[Override]
    public function deleteCallBack(Model $resource)
    {
        if ($resource->media) {
            MediaService::deleteMedia($resource->media->id);
        }
        return $resource;
    }
}

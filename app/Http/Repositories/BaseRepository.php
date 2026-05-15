<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BaseRepository
{
    //the model we are going to work with
    protected $model;

    protected $cacheEnabled = false; //ON/OFF Switch
    protected $cacheTime = 60; //how many minutes

    protected $baseName;
    public $modelName; //Product a
    public $modelNames;
    protected $modelKey;



    public function __construct()
    {
        $this->bootNaming();
    }
    protected function bootNaming()
    {
        $this->baseName = class_basename($this->model);
        $this->modelName = ucwords(preg_replace("/(?<!\ )[A-Z]/", ' $0', lcfirst($this->baseName)));
        //products->map to resources/views/products/
        $this->modelNames = Str::plural(Str::slug($this->modelName));
        $this->modelKey = Str::slug($this->modelName, '_');
    }

    public function withCache(int $minutes = 60)
    {
        $this->cacheEnabled = true;
        $this->cacheTime = $minutes;

        return $this;
    }

    public function withoutCache()
    {
        $this->cacheEnabled = false;
        return $this;
    }

    //generate a cache key for specific items
    protected function getCacheKey($id)
    {

        return $this->modelKey . '.' . $id;
        //product.1
    }

    //find a single record by id
    public function find($id)
    {
        $cacheKey = $this->getCacheKey($id);

        $result = Cache::remember(
            $cacheKey,
            $this->cacheTime,
            function () use ($id) {
                echo "fetching from DATABASE";
                return $this->model->find($id);
            }
        );

        if (!$this->cacheEnabled) {
            Cache::forget($cacheKey);
        }
        return $result;
    }

    public function findOrFail($id)
    {
        $cacheKey = $this->getCacheKey($id);

        $result = Cache::remember($cacheKey, $this->cacheTime, function () use ($id) {
            return $this->model->findOrFail($id);
        });

        if (!$this->cacheEnabled) {
            Cache::forget($cacheKey);
        }

        return $result;
    }

    public function all()
    {
        $cacheKey = $this->getCacheKey('all');

        $result = Cache::remember(
            $cacheKey,
            $this->cacheTime,
            function () {
                echo "->fetching all from database\n";
                return $this->model->all();
            }
        );

        if (!$this->cacheEnabled) {
            Cache::forget($cacheKey);
        }
        return $result;
    }

    public function create(array $data)
    {
        $record = $this->model->create($data);

        Cache::forget($this->getCacheKey('all'));

        return $record;
    }

    public function update($id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        //clear cache for this specific record
        Cache::forget($this->getCacheKey($id));
        //clear cache for all cache too
        Cache::forget($this->getCacheKey('all'));

        return $record;
    }

    public function delete($id)
    {
        $record = $this->model->findOrFail($id);
        $record->delete();

        //clear the specific cache
        Cache::forget($this->getCacheKey($id));
        Cache::forget($this->getCacheKey('all'));

        return true;
    }
}

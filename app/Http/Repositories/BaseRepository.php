<?php

namespace App\Http\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaseRepository
{
    //the model we are going to work with
    public Model $model;

    //for cache
    protected $cacheEnabled = false; //ON/OFF Switch
    protected $cacheTime = 60; //how many minutes


    protected $baseName;
    public $modelName;
    public $modelNames;
    protected $modelKey;

    //for relationship
    protected $relationships = []; //empty by default

    //for pagination
    public int $perPage = 5;
    protected string $orderField = 'created_at';
    protected string $orderDirection = 'desc';


    public function __construct()
    {
        $this->bootNaming();
    }
    protected function bootNaming()
    {
        //ProductCategory
        $this->baseName = class_basename($this->model);
        //Product Category
        $this->modelName = ucwords(preg_replace("/(?<!\ )[A-Z]/", ' $0', lcfirst($this->baseName)));
        //product-categories->map to resources/views/products/
        $this->modelNames = Str::plural(Str::slug($this->modelName));
        //product_categories
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
    //for pagination
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }
    //relationship method
    public function with(array $relationships): self
    {
        $this->relationships = array_unique(array_merge($this->relationships, $relationships));
        return $this;
    }
    //prepare model
    public function prepareModel(array $queryParams = []): Builder
    {
        $collection = $this->model->query();

        if ($this->relationships) {
            $collection = $collection->with($this->relationships);
        }
        if ($queryParams) {
            $collection = $collection->where($queryParams);
        }
        return $collection;
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
                return $this->prepareModel()->find($id);
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
            return $this->prepareModel()->findOrFail($id);
        });

        if (!$this->cacheEnabled) {
            Cache::forget($cacheKey);
        }

        return $result;
    }



    public function all(array $queryParams = [])
    {
        $cacheKey = $this->getCacheKey('all');

        $result = Cache::remember(
            $cacheKey,
            $this->cacheTime,
            function () use ($queryParams) {
                echo "->fetching all from database\n";
                return $this->prepareModel($queryParams)->get();
            }
        );

        if (!$this->cacheEnabled) {
            Cache::forget($cacheKey);
        }
        return $result;
    }
    public function index(array $queryParams = [])
    {

        $collection = $this->prepareModel($queryParams)
            ->orderBy($this->orderField, $this->orderDirection);

        return $this->perPage
            ? $collection->paginate($this->perPage)
            : $collection->get();
    }

    public function create(array $data, ?callable $callback = null)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->create($data);

            if ($callback) {
                $record = $callback($record) ?? $record;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
        Cache::forget($this->getCacheKey('all'));

        return $record;
    }

    public function update($id, array $data, ?callable $callback = null)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->findOrFail($id);
            $record->update($data);

            if ($callback) {
                $record = $callback($record) ?? $record;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        //clear cache for this specific record
        Cache::forget($this->getCacheKey($id));
        //clear cache for all cache too
        Cache::forget($this->getCacheKey('all'));

        return $record;
    }

    public function delete($id, ?callable $callback = null)
    {
        DB::beginTransaction();
        try {
            $record = $this->model->findOrFail($id);
            if ($callback) {
                $record = $callback($record) ?? $record;
            }
            $record->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        //clear the specific cache
        Cache::forget($this->getCacheKey($id));
        Cache::forget($this->getCacheKey('all'));

        return true;
    }
}

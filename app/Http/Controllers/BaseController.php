<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BaseController
{
    protected $repository;
    protected $modelNames;
    protected $collection; //products-list of product for views
    protected $resource; //product for single item
    protected $fileRoute; //products-view folder path 
    protected $formRequest;

    public function __construct()
    {
        $this->collection = $this->repository->modelNames;
        $this->resource = lcfirst($this->repository->modelName);
        $this->fileRoute = $this->repository->modelNames;
    }

    public function withCreate(): array
    {
        return []; //empty by default
    }

    public function create()
    {

        return view($this->fileRoute . '.create-edit')
            ->with(array_merge(
                [
                    $this->resource => $this->repository->model
                ],
                $this->withCreate(),

            ));
    }
    public function edit($id)
    {

        return view($this->fileRoute . '.create-edit')
            ->with(array_merge(
                [
                    $this->resource => $this->repository->findOrFail($id)
                ],
                $this->withCreate(),

            ));
    }

    public function show($id)
    {
        return view($this->fileRoute . '.show', [
            $this->resource => $this->repository->find($id)
        ]);
    }

    public function index()
    {
        return view($this->fileRoute . '.index')->with(
            [
                $this->collection => $this->repository->index()
            ]
        );
    }

    public function store()
    {
        $request = app($this->formRequest);
        try {
            $resource =  $this->repository->create(
                $this->processStoreData($request),
                function ($resource) use ($request) {
                    return $this->storeCallBack($resource, $request);
                }
            );
            return redirect()->route($this->fileRoute . '.index')->with('success', __('message.create_success', ['model' => $this->repository->modelName]));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', __('message.create_error', ['model' => $this->repository->modelName]));
        }
    }
    public function processStoreData(FormRequest $request)
    {
        return $request->validated();
    }
    public function storeCallBack(Model $resource, FormRequest $request)
    {
        return $resource;
    }
    public function updateCallBack(Model $resource, FormRequest $request)
    {
        return self::storeCallBack($resource, $request);
    }
    public function deleteCallBack(Model $resource)
    {
        return $resource;
    }


    public function update($id)
    {
        $request = app($this->formRequest);
        try {
            $this->repository->update(
                $id,
                $this->processStoreData($request),
                function ($resource) use ($request) {
                    return $this->updateCallBack($resource, $request);
                }
            );
            //for update and goback
            if ($request->action === 'back') {
                return redirect()->route($this->fileRoute . '.index')->with('success', __('message.update_success', ['model' => $this->repository->modelName]));
            }
            return redirect()->back()->with('success', __('message.update_success', ['model' => $this->repository->modelName]));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', __('message.update_error', ['model' => $this->repository->modelName]));
        };
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete(
                $id,
                function ($resource) {
                    return $this->deleteCallBack($resource);
                }
            );
            return redirect()->route($this->fileRoute . '.index')->with('success', __('message.delete_success', ['model' => $this->repository->modelName]));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('message.delete_error', ['model' => $this->repository->modelName]));
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController
{
    protected $repository;
    protected $modelNames;
    protected $collection; //products-list of product for views
    protected $resource; //product for single item
    protected $fileRoute; //products-view folder path 


    public function __construct()
    {
        $this->collection = $this->repository->modelNames;
        $this->resource = lcfirst($this->repository->modelName);
        $this->fileRoute = $this->repository->modelNames;
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
                $this->collection => $this->repository->all()
            ]
        );
    }
    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect()->route($this->fileRoute . '.index');
    }
    public function update($id, Request $request)
    {
        $this->repository->update($id, $request->all());

        return redirect()->back()->with('success', 'Updated Successfully!');
    }
    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route($this->fileRoute . '.index');
    }
}

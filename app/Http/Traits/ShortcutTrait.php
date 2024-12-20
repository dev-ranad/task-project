<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ShortcutTrait
{
    use QueryTrait, ApiResponserTrait, RelationTrait;
    abstract protected function model();
    abstract protected function validateData(Request $request, $resource_id = 0);

    protected function index(Request $request)
    {
        if (!in_array('index', $this->model()->action)) {
            return;
        }

        try {
            $model = $this->model()->model;
            $model = new $model;
            $table = $model->getTable();
            $name = $this->model()->name;
            $query = $model::latest();
            $data = $this->listQuery($query, $request, strtolower($name), $model);

            return $this->successResponse(
                $this->responseMessage($name, 'index'),
                $data
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function store(Request $request)
    {
        if (!in_array('store', $this->model()->action)) {
            return;
        }
        try {
            DB::beginTransaction();
            $model = $this->model()->model;
            if (method_exists($this, 'requestOperation')) $request = $this->requestOperation($request, 'store');
            if ($request->original && $request->original['success'] == false) return $this->errorResponse($request->original['message']);

            $dataOperation = dataOperation($this, $request);
            if ($dataOperation != null) return $dataOperation;

            if (method_exists($this, 'beforeOperation')) {
                $beforeOperation = $this->beforeOperation($request, 'store');
                if ($beforeOperation != null) return $beforeOperation;
            }

            $resource = $model::create($request->all());

            if ($resource && method_exists($this, 'afterOperation')) {
                $afterOperation = $this->afterOperation($request, $resource, 'store');
                if ($afterOperation != null) return $afterOperation;
            }

            $resource = $this->singleQuery($model, $resource, $request);

            DB::commit();
            return $this->successResponse(
                $this->responseMessage($this->model()->name, 'store'),
                $resource
            );
        } catch (Exception $exception) {
            DB::rollback();
            dd($exception);
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function show(Request $request, $resource_id)
    {
        if (!in_array('show', $this->model()->action)) {
            return;
        }

        try {
            $model = $this->model()->model;
            $name = $this->model()->name;
            $resource = $this->singleQuery($model, $query = null, $request, $resource_id);

            return $this->successResponse(
                $this->responseMessage($name, 'show'),
                $resource
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function update(Request $request, $resource_id)
    {
        if (!in_array('update', $this->model()->action)) {
            return;
        }

        try {
            DB::beginTransaction();
            $model = $this->model()->model;
            if (method_exists($this, 'requestOperation')) $request = $this->requestOperation($request, 'update', $resource_id);
            if ($request->original && $request->original['success'] == false) return $this->errorResponse($request->original['message']);

            $resource = $model::findOrFail($resource_id);

            $dataOperation = dataOperation($this, $request, $resource_id);
            if ($dataOperation != null) return $dataOperation;

            if (method_exists($this, 'resourceBaseRequest')) $request = $this->resourceBaseRequest($request, $resource);
            if (method_exists($this, 'beforeOperation')) $this->beforeOperation($request, 'update');

            $resource->update($request->all());

            if ($resource && method_exists($this, 'afterOperation')) $this->afterOperation($request, $resource, 'update');

            $query = $model::where('id', $resource->id);
            if ($request->has('child') && $request->child) $query = $this->loadRelationships($query, 'child', $request);
            if ($request->has('parent') && $request->parent) $query = $this->loadRelationships($query, 'parent', $request);

            $resource = $query->first();

            DB::commit();
            return $this->successResponse(
                $this->responseMessage($this->model()->name, 'update'),
                $resource
            );
        } catch (Exception $exception) {
            DB::rollback();
            return $this->errorResponse($exception->getMessage());
        }
    }

    protected function delete($resource_id)
    {
        if (!in_array('delete', $this->model()->action)) {
            return;
        }

        try {
            DB::beginTransaction();
            $resource = $this->model()->model::findOrFail($resource_id);

            $resource->delete();

            DB::commit();
            return $this->successResponse(
                $this->responseMessage($this->model()->name, 'delete'),
                null
            );
        } catch (Exception $exception) {
            DB::rollback();
            return $this->errorResponse($exception->getMessage());
        }
    }
}

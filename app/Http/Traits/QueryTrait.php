<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

trait QueryTrait
{
    public function singleQuery($model, $query = null, $request, $resource_id = null, $child = null)
    {
        if ($resource_id === null) $query = $model::where('id', $query->id);
        else $query = $model::where('id', $resource_id);

        if ($request->has('child') && $request->child) $query = $this->loadRelationships($query, 'child', $request);
        if ($request->has('parent') && $request->parent) $query = $this->loadRelationships($query, 'parent', $request);
        $resource = $query->first();
        return $resource;
    }

    public function listQuery($query, $request, $name, $model = null)
    {
        if ($request->has('child') && $request->child) $this->loadRelationships($query, 'child', $request);
        if ($request->has('parent') && $request->parent) $this->loadRelationships($query, 'parent', $request);

        $query = $this->searchQuery($query, $request);

        if ($request->has('allData') && $request->allData) return $this->pagination($name, $query, $request, $model);
        else {
            if ($request->has('group_by') && $request->group_by) {
                $results = $query->get()->groupBy(function ($item) use ($request) {
                    if (in_array($request->group_by, ['created_at'])) $group_by = $item->{$request->group_by}->format('Y-m-d');
                    else $group_by = $item->{$request->group_by};
                    return $group_by;
                });

                if ($request->has('summary')) {
                    $data = [
                        'summary' => $this->list_summary($request, $model),
                        $name => $results
                    ];
                } else {
                    $data = [$name => $results];
                }

                return ['results' => $data];
            } else {
                $results = $query->get();

                if ($request->has('summary')) {
                    $data = [
                        'summary' => $this->list_summary($request, $model),
                        $name => $results
                    ];
                } else {
                    $data = [$name => $results];
                }

                return ['results' => $data];
            }
        }
    }

    protected function list_summary($request, $model)
    {
        // Parse the summary request into module and data model
        [$module, $dataModule] = explode(',', $request->summary);
        $dataModule = str_replace('-', '_', $dataModule);
        $dataModel = pluralize($dataModule);

        // Define the summary queries
        $summaryQueries = [];

        // Define the date range fields for each model
        $dateRangeFields = [];

        // Get the query string for the current module
        $query = $summaryQueries[$dataModel] ?? null;

        // Check if the query and date range field are valid
        if ($query && isset($dateRangeFields[$dataModel])) {
            [$start_date, $end_date] = $this->getDateRange($request, $dateRangeFields[$dataModel]);

            // Fetch data for regular modules
            return DB::table($dataModel)
                ->where("{$module}_id", $request->input("{$module}_id"))
                ->where($this->getDateField($dataModel, $dataModule), '>=', $start_date)
                ->where($this->getDateField($dataModel, $dataModule), '<=', $end_date)
                ->selectRaw($query)
                ->get();
        }

        return response()->json(['error' => 'Invalid summary type or missing date range'], 400);
    }

    protected function getDateRange($request, $dateField)
    {
        $dates = dataExplode($request, $dateField);
        $start_date = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
        return [$start_date, $end_date];
    }

    protected function getDateField($dataModel, $dataModule)
    {
        return Schema::hasColumn($dataModel, "{$dataModule}_at") ? "{$dataModule}_at" : 'created_at';
    }





    protected function pagination($module, $query, $request, $model = null)
    {
        $query = $this->searchQuery($query, $request);
        $results = $query->paginate($request->perPage ?? 10);

        if ($request->has('summary')) {
            $data = [
                'summary' => $this->list_summary($request, $model),
                $module => $results->items()
            ];
        } else $data = [$module => $results->items()];

        return [
            'results' => $data,
            'meta' => [
                'total' => $model::count(),
                'per_page' => $request->perPage,
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'next_page_url' => $results->nextPageUrl(),
                'prev_page_url' => $results->previousPageUrl(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem(),
            ]
        ];
    }

    protected function searchQuery($query, $request)
    {
        if ($request->has('keyword')) {
            if (checkColumn($query, 'name')) $query->where('name', 'like', '%' . $request->keyword . '%');
            if (checkColumn($query, 'sku')) $query->orWhere('sku', 'like', '%' . $request->keyword . '%');
            if (checkColumn($query, 'price')) $query->orWhere('price', 'like', '%' . $request->keyword . '%');
            if (checkColumn($query, 'quantity')) $query->orWhere('quantity', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('sortBy')) {
            $sortOptions = [
                'Newest'        => fn() => $query->latest(),
                'Oldest'        => fn() => $query->orderBy('created_at', 'asc'),
                'A to Z'        => fn() => checkColumn($query, 'name') ? $query->orderBy('name', 'asc') : null,
                'Z to A'        => fn() => checkColumn($query, 'name') ? $query->orderBy('name', 'desc') : null,
                'Last Updated'  => fn() => checkColumn($query, 'updated_at') ? $query->latest('updated_at') : null,
            ];

            $sortOptions[$request->sortBy]();
        }

        if ($request->has('start_date') && checkColumn($query, 'created_at')) {
            $start_date = Carbon::createFromFormat('Y-m-d', trim($request->start_date))->startOfDay();
            $end_date = Carbon::createFromFormat('Y-m-d', trim($request->end_date))->endOfDay();
            $query->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);
        }

        return $query;
    }
}

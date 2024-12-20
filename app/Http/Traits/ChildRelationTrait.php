<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Schema;

trait ChildRelationTrait
{
    // Define relationships


    public function loadChildren($table, $query, $request)
    {
        $relations = [];

        if (!empty($relations)) {
            foreach ($relations as $relation) {
                $query = $this->handleQuery($query, $relation, $request[$relation]);
            }
        }

        return $query;
    }
}

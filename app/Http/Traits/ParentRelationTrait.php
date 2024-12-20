<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Schema;

trait ParentRelationTrait
{
    // Parent Table: parents(DB)
    // The parents(DB) table is the parent table because each parent can have
    // multiple childs(DB). It is the source of the primary key (id) that is
    // referenced by the foreign key in the child table.



    public function loadParent($table, $query, $columns = null)
    {
        $relations = [];

        if (!empty($relations)) {
            foreach ($relations as $relation) {
                $query = $this->handleQuery($query, $relation, $columns);
            }
        }
        return $query;
    }
}

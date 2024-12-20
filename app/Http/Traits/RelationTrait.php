<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Notifications\Notifiable;
use ReflectionClass;
use ReflectionMethod;

trait RelationTrait
{
    use ParentRelationTrait, ChildRelationTrait, Notifiable;


    public function getDynamicAttributes()
    {
        return getDynamicAttributes($this);
    }

    public function handleQuery($query = null, $relatedData = null, $columns = null)
    {
        if ($query) {
            if (Schema::hasColumn($query->getModel()->getTable(), 'deleted_at')) {
                return $query->with($relatedData)->withTrashed();
            } else {
                return $query->with($relatedData);
            }
        }
    }

    // Dynamically get relation data based on table columns
    protected function loadRelationships($query, $need, $request = null)
    {
        $model = $query->getModel();
        $table = $model->getTable();

        if ($need == 'child') {
            $query = $this->loadChildren($table, $query, $request);
        }
        if ($need == 'parent') {
            $query = $this->loadParent($table, $query, $request);
        }

        return $query;
    }

    function tableToModel($string = null)
    {
        // Replace underscores with spaces
        $string = str_replace('_', ' ', $string);
        // Capitalize the first letter of each word
        $string = ucwords($string);
        // Replace spaces back with underscores
        $string = str_replace(' ', '', singularize($string));
        // $string = singularize($string);
        return $string;
    }

    function getRelationships($table)
    {
        $model = new $table;
        $relationships = [];

        $reflection = new ReflectionClass($model);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            if ($method->class === get_class($model) && $method->getNumberOfParameters() === 0) {
                try {
                    $return = $method->invoke($model);

                    if ($return instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                        $relationType = (new ReflectionClass($return))->getShortName();
                        $relatedModel = get_class($return->getRelated());
                        $relationships[$method->getName()] = [
                            'type' => $relationType,
                            'model' => $relatedModel
                        ];
                    }
                } catch (\Exception $e) {
                    // Ignore methods that are not relationships
                }
            }
        }

        return $relationships;
    }

    function hasRelationship(Model $model, $relationship)
    {
        $reflection = new ReflectionClass($model);
        return $reflection->hasMethod($relationship);
    }
}

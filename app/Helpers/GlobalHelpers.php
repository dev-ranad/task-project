<?php

use App\Http\Services\HelperService;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


function makeReference($word)
{
    return strtoupper(substr($word, 0, 3)) . '-' . date('ymd') . '-' . rand(11111, 99999);
}

function dataOperation($that, $request, $resource_id = 0)
{
    // Validate the request data
    $validate = $that->validateData($request, $resource_id);
    $service = new HelperService();

    if ($validate->fails()) {
        $operation = $service->errorResponse(
            $service->responseMessage($that->model()->name, 'validation'),
            $validate->messages()->messages()
        );
    }

    return $operation ?? null;
}

function loadUser($user_id)
{
    $user = User::findOrFail($user_id);
    return $user;
}

function singularize($word)
{
    // Check if the word ends in 'ies'
    if (preg_match('/ies$/', $word)) {
        // Replace 'ies' with 'y'
        $singular = preg_replace('/ies$/', 'y', $word);
    }
    // Check if the word ends in 's'
    elseif (preg_match('/s$/', $word)) {
        // Remove 's'
        $singular = preg_replace('/s$/', '', $word);
    }
    // Check if the word ends in 'es'
    elseif (preg_match('/es$/', $word)) {
        // Remove 'es'
        $singular = preg_replace('/es$/', '', $word);
    } else {
        // If the word does not end in 's', 'es', or 'ies', return it as it is
        $singular = $word;
    }

    return $singular;
}

function pluralize($word)
{
    // Apply regular pluralization rules
    if (preg_match('/(s|x|z|ch|sh)$/i', $word)) {
        // Words ending in s, x, z, ch, or sh add "es"
        return $word . 'es';
    } elseif (preg_match('/[^aeiou]y$/i', $word)) {
        // Words ending in consonant + y change y to ies
        return substr($word, 0, -1) . 'ies';
    } elseif (preg_match('/(f|fe)$/i', $word)) {
        // Words ending in f or fe change to ves
        return preg_replace('/(f|fe)$/i', 'ves', $word);
    } elseif (preg_match('/o$/i', $word)) {
        // Words ending in o (with some exceptions) add es
        return $word . 'es';
    } else {
        // Default rule: add s
        return $word . 's';
    }
}

function getDynamicAttributes($that)
{
    $attributes = collect(Schema::getColumnListing($that->getTable()));
    $relations = collect(getRelationships($that));
    // dd($relations);
    return $attributes->merge($relations->flatMap(function ($relation) use ($that) {
        return collect(Schema::getColumnListing($that->$relation()->getRelated()->getTable()))
            ->map(function ($field) use ($relation) {
                return $relation . '.' . $field;
            });
    }))->toArray();
}

function getRelationships($that)
{
    $methods = collect(get_class_methods($that))
        ->filter(function ($method) use ($that) {
            $reflection = new ReflectionMethod($that, $method);
            $returnType = $reflection->getReturnType();
            if ($returnType !== null) return $reflection->isPublic()
                && $reflection->getNumberOfParameters() === 0
                && Str::endsWith($returnType->getName(), 'Relations\\Relation');
        })->values();

    return $methods;
}

function dataExplode($request, $value)
{
    $explode = explode(',', $request->{$value});
    return $explode;
}

function checkColumn($query, $value)
{
    return Schema::hasColumn($query->getModel()->getTable(), $value);
}

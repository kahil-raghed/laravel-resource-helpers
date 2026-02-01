<?php

namespace LaravelResourceHelpers\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use LaravelResourceHelpers\ResourceHelpers;

/**
 * 
 * @mixin JsonResource
 */
trait WithOptional
{
    /**
     * Get an optional value from the resource, e.g. when the value might be missing or not selected from database.
     */
    protected function optional(string $key, callable|null $transform = null): mixed
    {
        $transform ??= fn($value) => $value;

        $modelClass = ResourceHelpers::baseModel();
        
        $resource = $this->resource;


        if (\is_array($resource)) {
            return array_key_exists($key, $resource) ? $transform($resource[$key]) : new MissingValue();
        } elseif (class_exists($modelClass) && $resource instanceof $modelClass) {
            return array_key_exists($key, $resource->getOriginal()) ? $transform($resource->{$key} ?? null) : new MissingValue();
        } else {
            return property_exists($resource, $key) ? $transform($resource->{$key} ?? null) : new MissingValue();
        }
    }
}
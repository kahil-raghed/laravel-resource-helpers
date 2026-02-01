<?php

namespace LaravelResourceHelpers\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelResourceHelpers\ResourceHelpers;

/**
 * 
 * @mixin JsonResource
 */
trait WithEnum
{
    use WithOptional;

    protected function enum(string $key): mixed
    {
        $enumResourceClass = ResourceHelpers::enumResource();
        $value = $this->{$key};
        return $value != null ? $enumResourceClass::make($value) : null;
    }

    protected function optionalEnum(string $key): mixed
    {
        $enumResourceClass = ResourceHelpers::enumResource();
        return $this->optional($key, fn($value) => $value != null ? $enumResourceClass::make($value) : null);
    }
}
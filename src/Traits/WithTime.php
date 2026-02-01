<?php

namespace LaravelResourceHelpers\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelResourceHelpers\ResourceHelpers;

/**
 * 
 * @mixin JsonResource
 */
trait WithTime
{
    use WithOptional;

    protected function time(string $key): mixed
    {
        $value = $this->{$key};
        return $value ? $value->format(ResourceHelpers::timeFormat()) : null;
    }

    protected function optionalTime(string $key): mixed
    {
        $timeFormat = ResourceHelpers::timeFormat();

        return $this->optional($key, fn($value) => $value ? $value->format($timeFormat) : null);
    }
}
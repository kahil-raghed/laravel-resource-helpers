<?php

namespace LaravelResourceHelpers\Traits;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 
 * @mixin JsonResource
 */
trait WithAsset
{
    use WithOptional;

    protected function asset(string $key): mixed
    {
        $value = $this->{$key};
        return $value != null ? asset('storage/' . $value) : null;
    }

    protected function assets(string $key): mixed
    {
        $values = $this->{$key};
        if (is_array($values) || $values instanceof \Illuminate\Support\Collection) {
            return collect($values)->map(fn($value) => $value != null ? asset('storage/' . $value) : null)->all();
        }
        return null;
    }

    protected function optionalAsset(string $key): mixed
    {
        return $this->optional($key, fn($value) => $value != null ? asset('storage/' . $value) : null);
    }
}
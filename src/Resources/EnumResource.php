<?php


namespace LaravelResourceHelpers\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @mixin \BackedEnum
 */
class EnumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            $this->mergeWhen(
                $this->resource instanceof \BackedEnum,
                fn() => [
                    'value' => $this->value,
                ],
            ),
            $this->mergeWhen(method_exists($this->resource, 'label'), fn() => [
                'label' => $this->resource->label(),
            ]),
        ];
    }
}
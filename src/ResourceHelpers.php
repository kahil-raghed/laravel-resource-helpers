<?php

namespace LaravelResourceHelpers;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceHelpers
{
    private function __construct()
    {
        // Prevent instantiation
    }
    protected static string $baseModel = "Illuminate\Database\Eloquent\Model";

    public static function baseModel(?string $modelClass = null): string
    {
        if ($modelClass !== null) {
            static::$baseModel = $modelClass;
        }

        return static::$baseModel;
    }

    protected static string $enumResource = \LaravelResourceHelpers\Resources\EnumResource::class;

    /**
     * @param class-string<JsonResource> $resourceClass
     * @return string
     */
    public static function enumResource(?string $resourceClass = null): string
    {
        if ($resourceClass !== null) {
            static::$enumResource = $resourceClass;
        }

        return static::$enumResource;
    }


    protected static string $dateFormat = 'Y-m-d';

    public static function dateFormat(?string $format = null): string
    {
        if ($format !== null) {
            static::$dateFormat = $format;
        }

        return static::$dateFormat;
    }

    protected static string $timeFormat = 'H:i:s';

    public static function timeFormat(?string $format = null): string
    {
        if ($format !== null) {
            static::$timeFormat = $format;
        }

        return static::$timeFormat;
    }

    protected static string $datetimeFormat = 'Y-m-d H:i:s';

    public static function datetimeFormat(?string $format = null): string
    {
        if ($format !== null) {
            static::$datetimeFormat = $format;
        }

        return static::$datetimeFormat;
    }
}
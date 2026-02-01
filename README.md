# Laravel Resource Helpers

A collection of useful traits for Laravel API Resources that simplify common data transformation tasks.

## Overview

Laravel Resource Helpers is a lightweight package that provides reusable traits for Laravel's API Resources. It helps you handle common scenarios like formatting dates, transforming enums, handling optional fields, and generating asset URLs with minimal boilerplate code.

## Requirements

- PHP >= 8.0
- Laravel 8.x, 9.x, 10.x, 11.x, 12.x
- Carbon ^2.73

## Installation

Install the package via Composer:

```bash
composer require kahil-raghed/laravel-resource-helpers
```

## Features

### Available Traits

- **WithResourceHelpers** - Main trait that includes all other traits
- **WithOptional** - Handle optional/conditionally selected fields
- **WithAsset** - Transform storage paths to full asset URLs
- **WithDate** - Format date fields
- **WithDatetime** - Format datetime fields
- **WithTime** - Format time fields
- **WithEnum** - Transform PHP enums to structured arrays

## Configuration

### Global Configuration

You can customize default formats globally using the `ResourceHelpers` class:

```php
use LaravelResourceHelpers\ResourceHelpers;

// In a service provider's boot method
ResourceHelpers::dateFormat('d/m/Y');
ResourceHelpers::timeFormat('h:i A');
ResourceHelpers::datetimeFormat('d/m/Y h:i A');
```

### Custom Base Model

If you're using a custom base model class instead of Eloquent's default:

```php
ResourceHelpers::baseModel(YourCustomModel::class);
```

### Custom Enum Resource

To use a custom enum resource instead of the default:

```php
ResourceHelpers::enumResource(YourCustomEnumResource::class);
```

## Usage

### Quick Start

Include the main trait in your API Resource:

```php
use Illuminate\Http\Resources\Json\JsonResource;
use LaravelResourceHelpers\Traits\WithResourceHelpers;

class UserResource extends JsonResource
{
    use WithResourceHelpers;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->datetime('created_at'),
            'birth_date' => $this->optionalDate('birth_date'),
            'avatar' => $this->asset('avatar'),
            'status' => $this->enum('status'),
        ];
    }
}
```

### WithOptional

Handle fields that might not be loaded or selected from the database:

```php
public function toArray($request)
{
    return [
        'id' => $this->id,
        'email' => $this->optional('email'),
        'phone' => $this->optional('phone', fn($value) => format_phone($value)),
    ];
}
```

**Methods:**
- `optional(string $key, callable|null $transform = null): mixed` - Returns the field value if it exists, or a `MissingValue` if not (which Laravel will exclude from the response)
- If the field exists but has null value it will be returned

### WithAsset

Transform storage paths to full asset URLs:

```php
public function toArray($request)
{
    return [
        'avatar' => $this->asset('avatar'),
        'cover_image' => $this->optionalAsset('cover_image'),
        'gallery' => $this->assets('gallery'), // For arrays/collections
    ];
}
```

**Methods:**
- `asset(string $key): mixed` - Converts `path/to/file.jpg` to `http://yoursite.com/storage/path/to/file.jpg`
- `assets(string $key): mixed` - Same as `asset()` but for arrays or collections of paths
- `optionalAsset(string $key): mixed` - Optional version that handles missing fields

### WithDate

Format date fields with a consistent format:

```php
public function toArray($request)
{
    return [
        'birth_date' => $this->date('birth_date'),
        'joined_date' => $this->optionalDate('joined_date'),
    ];
}
```

**Methods:**
- `date(string $key): mixed` - Formats a date using the configured format (default: `Y-m-d`)
- `optionalDate(string $key): mixed` - Optional version that handles missing fields

**Default format:** `Y-m-d` (e.g., `2026-02-01`)

### WithDatetime

Format datetime fields:

```php
public function toArray($request)
{
    return [
        'created_at' => $this->datetime('created_at'),
        'updated_at' => $this->optionalDatetime('updated_at'),
    ];
}
```

**Methods:**
- `datetime(string $key): mixed` - Formats a datetime using the configured format (default: `Y-m-d H:i`)
- `optionalDatetime(string $key): mixed` - Optional version that handles missing fields

**Default format:** `Y-m-d H:i` (e.g., `2026-02-01 14:30`)

### WithTime

Format time fields:

```php
public function toArray($request)
{
    return [
        'start_time' => $this->time('start_time'),
        'end_time' => $this->optionalTime('end_time'),
    ];
}
```

**Methods:**
- `time(string $key): mixed` - Formats a time using the configured format (default: `H:i`)
- `optionalTime(string $key): mixed` - Optional version that handles missing fields

**Default format:** `H:i` (e.g., `14:30`)

### WithEnum

Transform PHP enums to structured arrays:

```php
enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    
    public function label(): string
    {
        return match($this) {
            self::Active => 'Active User',
            self::Inactive => 'Inactive User',
            self::Suspended => 'Suspended User',
        };
    }
}

class UserResource extends JsonResource
{
    use WithResourceHelpers;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->enum('status'),
            'role' => $this->optionalEnum('role'),
        ];
    }
}
```

**Output:**
```json
{
    "id": 1,
    "status": {
        "name": "Active",
        "value": "active",
        "label": "Active User"
    }
}
```

**Methods:**
- `enum(string $key): mixed` - Transforms an enum to a resource
- `optionalEnum(string $key): mixed` - Optional version that handles missing fields

The `EnumResource` includes:
- `name` - The enum case name
- `value` - The backed value (only for `BackedEnum`)
- `label` - Custom label if a `label()` method is defined on the enum


## Advanced Examples

### Combining Multiple Traits

You can use individual traits if you don't need all features:

```php
use LaravelResourceHelpers\Traits\WithOptional;
use LaravelResourceHelpers\Traits\WithDate;
use LaravelResourceHelpers\Traits\WithAsset;

class ProductResource extends JsonResource
{
    use WithOptional, WithDate, WithAsset;

    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'image' => $this->asset('image'),
            'release_date' => $this->optionalDate('release_date'),
        ];
    }
}
```

### Selective Field Loading

The `optional()` method works great with Laravel's selective column loading:

```php
// Controller
public function index()
{
    // Only load specific columns
    $users = User::select(['id', 'name', 'email'])->get();
    
    return UserResource::collection($users);
}

// Resource
class UserResource extends JsonResource
{
    use WithResourceHelpers;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->optional('email'),
            'phone' => $this->optional('phone'), // Won't be included since not selected
            'address' => $this->optional('address'), // Won't be included since not selected
        ];
    }
}
```

### Custom Transformations

The `optional()` method accepts a transformation callback:

```php
public function toArray($request)
{
    return [
        'price' => $this->optional('price', fn($value) => number_format($value, 2)),
        'phone' => $this->optional('phone', fn($value) => preg_replace('/[^0-9]/', '', $value)),
        'tags' => $this->optional('tags', fn($value) => explode(',', $value)),
    ];
}
```

## API Reference

### ResourceHelpers Class

Static configuration class for global settings.

#### Methods

- `baseModel(?string $modelClass = null): string` - Get or set the base model class
- `enumResource(?string $resourceClass = null): string` - Get or set the enum resource class
- `dateFormat(?string $format = null): string` - Get or set the date format
- `timeFormat(?string $format = null): string` - Get or set the time format
- `datetimeFormat(?string $format = null): string` - Get or set the datetime format

### Default Values

- **Date Format:** `Y-m-d`
- **Time Format:** `H:i`
- **Datetime Format:** `Y-m-d H:i`
- **Base Model:** `Illuminate\Database\Eloquent\Model`
- **Enum Resource:** `LaravelResourceHelpers\Resources\EnumResource`

## How It Works

### Optional Fields

The `WithOptional` trait checks if a field exists in the underlying resource:
- For arrays: checks if the key exists
- For Eloquent models: checks if the attribute exists in the original attributes
- For other objects: checks if the property exists

If the field doesn't exist, it returns a `MissingValue` instance which Laravel's JSON resource will automatically exclude from the response.

### Asset URLs

The `WithAsset` trait prepends `storage/` to the path and uses Laravel's `asset()` helper to generate the full URL. This assumes your files are stored in the public storage directory.

### Date/Time Formatting

Date, time, and datetime traits use Carbon instances and format them according to the configured formats. They handle null values gracefully.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.

## Author

**Raghed Kahil**
- Email: kahilraghed@gmail.com

## Credits

Built with ❤️ for the Laravel community.

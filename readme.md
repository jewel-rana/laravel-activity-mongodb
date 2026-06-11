# Welcome to mongovity package

Application activity logging for Laravel using MongoDB.

## Requirements

- PHP 8.4+
- Laravel 12+
- [mongodb/laravel-mongodb](https://github.com/mongodb/laravel-mongodb) (official MongoDB Laravel integration)
- PHP `mongodb` extension (`pecl install mongodb`)

## Upgrading from jenssegers/mongodb

This package previously depended on the abandoned `jenssegers/mongodb` package. It now uses the officially maintained `mongodb/laravel-mongodb` package.

In your Laravel application:

1. Remove the old package and install the official one:

```bash
composer remove jenssegers/mongodb
composer require mongodb/laravel-mongodb
```

2. Update your MongoDB model imports from:

```php
use Jenssegers\Mongodb\Eloquent\Model;
```

to:

```php
use MongoDB\Laravel\Eloquent\Model;
```

3. Update `config/database.php` if needed. The service provider is auto-discovered as `MongoDB\Laravel\MongoDBServiceProvider`.

4. Upgrade this package to the latest version:

```bash
composer require rajtika/mongovity
```

No changes are required to your mongovity usage (`ActivityTrait`, `Mongovity` service, or facade). Existing activity log data remains compatible.

## Installation

```bash
composer require rajtika/mongovity
php artisan vendor:publish --provider="Rajtika\Mongovity\MongovityServiceProvider"
```

## Implementation

- Set your MongoDB connection and connection name in the `mongovity.php` config file.
- If you want to log all model activity automatically, follow the instructions below.

```php
// Add ActivityTrait to the model you want to log
use Rajtika\Mongovity\Contracts\ActivityTrait;

// You can define specific events to be logged
protected $recordEvents = [
    'created',
    'updated',
    'deleted',
    'restored'
];
/** -----------OR--------- **/
protected $loggableEvents = [
    'created',
    'updated',
    'deleted',
    'restored'
];

// If you want to save a custom activity log
use Rajtika\Mongovity\Services\Mongovity;

app(Mongovity::class)
    ->by(Auth::user()) // Required *
    ->on(TestModel::find(1)) // optional
    ->event('created') // optional
    ->log('Your custom message');

/** OR **/
use Rajtika\Mongovity\Facades\Mongovity;

Mongovity::by(User::first())->log('Custom message');
```

If your user table has `first_name` and `last_name`, add this accessor to log the user name:

```php
public function getNameAttribute()
{
    return $this->first_name . ' ' . $this->last_name;
}
```

If your user table uses a column other than `mobile`, add this accessor:

```php
public function getMobileAttribute()
{
    return $this->mobile_number;
}
```

To access the built-in activity log UI, assign the `admin` role or the `activity_logs` permission to the user (when using Spatie Laravel Permission).

## Configuration

| Key | Description | Default |
|-----|-------------|---------|
| `connection_name` | MongoDB database connection | `mongodb` |
| `collection_name` | MongoDB collection for logs | `activity_logs` |
| `causer_model` | User model class for causer references | `App\Models\User` |
| `log_name` | Application log identifier | `APP_NAME` |
| `route_middleware` | Middleware for mongovity routes | `['web', 'auth']` |
| `index_route_middleware` | Permission middleware for the log UI | `role_or_permission:admin\|activity_logs` |

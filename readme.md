# Welcome to mongovity package

## Requirements
- jenssegers/laravel-mongodb
- php mongodb extension
- php7.4 or higher

## Installation
composer require rajtika/mongovity
php artisan vendor:publish --provider="Rajtika\Mongovity\MongovityServiceProvider"

## Implementation
- set your mongodb connection and give connection name in the mongovity.php config file
  - If you want to log all model activity automatically follow the instructions below
```php
//Add ActivityTrait in you model which you want to log
use Rajtika\Mongovity\Contracts\ActivityTrait;

//You can define specefic events to be logged by
protected $recordEvents = [
    'created',
    'updated',
    'deleted',
    'restored'
];

//If you want to save your custom activity log
use Rajtika\Mongovity\Services\Mongovity;

    app(Mongovity::class)
    ->by(Auth::user()) //Required *
    ->on(TestModel::find(1)) // optional
    ->event('created') // optional
    ->log('Your custom message');
```

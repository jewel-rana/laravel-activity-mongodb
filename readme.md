# Welcome to mongovity package

## Requirements
- jenssegers/laravel-mongodb
- php mongodb extension
- php7.4 or higher

## Installation
- composer require rajtika/mongovity
- php artisan vendor:publish --provider="Rajtika\Mongovity\MongovityServiceProvider"

## Implementation
- set your mongodb connection and give connection name in the mongovity.php config file
  - If you want to log all model activity automatically follow the instructions below
    - Use ActivityTrait in your model
    - You can define model events to be logged
    - public $recordEvents = ['created', 'updated', 'deleted', 'restored']; // these are default events to be recorded
    - For custom log
    app(Mongovity::class)
    ->by(Model object who caused) //Required
    ->on(Model Object which caused) // optional
    ->event('deleted') // optional
    ->log('Your custom message');

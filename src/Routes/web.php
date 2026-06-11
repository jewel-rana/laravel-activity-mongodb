<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::group([
    'prefix' => 'mongovity',
    'middleware' => config('mongovity.route_middleware', ['web', 'auth']),
], function () {
    Route::get('/', [MongoActivityController::class, 'index'])
        ->middleware(config('mongovity.index_route_middleware'))
        ->name(Mongovity::NAMESPACE);
    Route::get('test', [MongoActivityController::class, 'test']);
});

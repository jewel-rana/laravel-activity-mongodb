<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::group(['prefix' => 'mongovity', 'middleware' => config(Mongovity::NAMESPACE . '.route_middleware', 'auth')], function() {
    Route::get('/', [MongoActivityController::class, 'index'])
        ->name(Mongovity::NAMESPACE);
    Route::get('test', [MongoActivityController::class, 'test']);
});

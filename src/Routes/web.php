<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::get('mongovity', [MongoActivityController::class, 'index'])
    ->name(Mongovity::NAMESPACE)
    ->middleware(config(Mongovity::NAMESPACE . '.route_middleware'));

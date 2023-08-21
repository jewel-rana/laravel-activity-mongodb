<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::get('mongovity', [MongoActivityController::class, 'index'])
    ->middleware(config(\Rajtika\Mongovity\Constants\Mongovity::NAMESPACE . '.route_middleware'));

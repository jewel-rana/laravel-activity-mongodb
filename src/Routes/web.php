<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::group(['prefix' => 'mongovity', 'middleware' => ['web', 'session_bind_user_agent']], function() {
    Route::get('/', [MongoActivityController::class, 'index'])
        ->middleware('role_or_permission:admin|activity_logs')
        ->name(Mongovity::NAMESPACE);
    Route::get('test', [MongoActivityController::class, 'test']);
});

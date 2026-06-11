<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Constants\Mongovity;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::group([
    'prefix' => 'mongovity',
    'middleware' => config('mongovity.route_middleware', ['web', 'auth']),
], function () {
    $indexRoute = Route::get('/', [MongoActivityController::class, 'index']);

    $indexMiddleware = config('mongovity.index_route_middleware')
        ?? 'role_or_permission:admin|activity_logs';

    if ($indexMiddleware !== null && $indexMiddleware !== '') {
        $indexRoute->middleware($indexMiddleware);
    }

    $indexRoute->name(Mongovity::NAMESPACE);

    Route::get('test', [MongoActivityController::class, 'test']);
});

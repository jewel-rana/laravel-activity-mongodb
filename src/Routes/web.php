<?php

use Illuminate\Support\Facades\Route;
use Rajtika\Mongovity\Http\Controllers\MongoActivityController;

Route::get('mongovity', [MongoActivityController::class, 'index']);

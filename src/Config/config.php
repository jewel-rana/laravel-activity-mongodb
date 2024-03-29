<?php

/**
 * Mongodb activity package config file
 */
return [
    /**
     * This will be connection name of your database connection for mongodb
     */
    'connection_name' => env('MONGO_VITY_CONNECTION', 'mongodb'),

    /**
     * This model will be use as causer type references
     */
    'causer_model' => \App\Models\User::class,

    /**
     * log_name will be use if you use this from multiple application
     * so, package will automatically provide your applications log in the built-in UI
     */
    'log_name' => env("APP_NAME", 'default'),

    /**
     * Secure default mongovity route to see the logs
     */
    'route_middleware' => env('MONGO_VITY_ROUTE_MIDDLEWARE', 'auth')
];




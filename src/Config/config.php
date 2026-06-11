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
     * MongoDB collection name for activity logs
     */
    'collection_name' => env('MONGO_VITY_COLLECTION', 'activity_logs'),

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
     * Middleware applied to all mongovity routes
     */
    'route_middleware' => ['web', 'auth'],

    /**
     * Additional middleware for the activity log index route
     */
    'index_route_middleware' => env(
        'MONGO_VITY_INDEX_MIDDLEWARE',
        'role_or_permission:admin|activity_logs'
    ),

    /**
     * Application layout used by the activity log page.
     * Set this to your app layout (e.g. layouts.app) to avoid loading
     * duplicate jQuery, Bootstrap, and DataTables assets.
     */
    'layout' => env('MONGO_VITY_LAYOUT'),

    /**
     * Blade section / stack names used when embedding in the application layout.
     */
    'content_section' => env('MONGO_VITY_CONTENT_SECTION', 'content'),
    'styles_stack' => env('MONGO_VITY_STYLES_STACK', 'styles'),
    'scripts_stack' => env('MONGO_VITY_SCRIPTS_STACK', 'scripts'),

    /**
     * Skip loading Moment.js and DateRangePicker assets when your app already provides them.
     */
    'skip_daterangepicker_assets' => env('MONGO_VITY_SKIP_DATERANGEPICKER_ASSETS', false),
];




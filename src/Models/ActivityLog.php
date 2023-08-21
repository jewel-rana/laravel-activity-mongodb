<?php

namespace Rajtika\Mongovity\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';
    protected $fillable = ['causer_id', 'causer_type', 'causer_name', 'causer_mobile', 'subject_id', 'subject_type', 'message', 'data', 'log_name'];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function(ActivityLog $activityLog) {
            if(!$activityLog->log_name) {
                $activityLog->log_name = 'default';
            }
        });
    }
}

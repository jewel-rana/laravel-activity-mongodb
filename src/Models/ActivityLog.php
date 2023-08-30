<?php

namespace Rajtika\Mongovity\Models;

use Illuminate\Support\Facades\Log;
use Jenssegers\Mongodb\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';
    protected $fillable = ['causer_id', 'causer_type', 'causer_name', 'causer_mobile', 'subject_id', 'subject_type', 'message', 'data', 'ip', 'log_name'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $dates = ['created_at'];

    public static function boot()
    {
        parent::boot();
        static::creating(function (ActivityLog $activityLog) {
            try {
                if (!$activityLog->ip) {
                    $activityLog->ip = request()->ip();
                }
                if (!$activityLog->log_name) {
                    $activityLog->log_name = config('mongovity.log_name', 'default');
                }
                $activityLog->data = $activityLog->data + [
                        'hosts' => [
                            'name' => gethostname(),
                            'uri' => $_SERVER['REQUEST_URI']
                        ]
                    ];
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        });
    }
}

<?php

namespace Rajtika\Mongovity\Models;

use Illuminate\Support\Facades\Log;
use MongoDB\Laravel\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'causer_id',
        'causer_type',
        'causer_name',
        'causer_mobile',
        'subject_id',
        'subject_type',
        'message',
        'data',
        'ip',
        'log_name',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
    ];

    public function format(): array
    {
        return $this->attributesToArray() + [
            'causer_mobile' => '',
            'causer_name' => '',
            'subject_id' => '',
            'subject_type' => '',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ActivityLog $activityLog) {
            try {
                if (! $activityLog->ip) {
                    $activityLog->ip = request()->ip();
                }
                if (! $activityLog->log_name) {
                    $activityLog->log_name = config('mongovity.log_name', 'default');
                }
                $activityLog->data = ($activityLog->data ?? []) + [
                    'hosts' => [
                        'name' => gethostname(),
                        'uri' => $_SERVER['REQUEST_URI'] ?? null,
                    ],
                ];
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        });
    }
}

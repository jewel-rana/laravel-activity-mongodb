<?php

namespace Rajtika\Mongovity\Models;

use Illuminate\Support\Facades\Log;
use MongoDB\Laravel\Eloquent\Model;

class ActivityLog extends Model
{
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
        'created_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        $this->connection = config('mongovity.connection_name', 'mongodb');
        $this->collection = config('mongovity.collection_name', 'activity_logs');

        parent::__construct($attributes);
    }

    public function format(): array
    {
        $attributes = $this->attributesToArray();

        if ($this->created_at) {
            $attributes['created_at'] = $this->created_at->format('d/m/Y H:i:s');
        }

        return $attributes + [
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
                if (! $activityLog->created_at) {
                    $activityLog->created_at = now();
                }
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

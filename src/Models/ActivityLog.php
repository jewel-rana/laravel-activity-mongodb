<?php

namespace Rajtika\Mongovity\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class ActivityLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'activity_logs';
    protected $fillable = ['causer_id', 'causer_type', 'subject_id', 'subject_type', 'message', 'data'];
}

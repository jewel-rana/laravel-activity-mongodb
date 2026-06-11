<?php

namespace Rajtika\Mongovity\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Rajtika\Mongovity\Services\Mongovity;

trait ActivityTrait
{
    protected static function bootActivityTrait(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                if ($eventName === 'updated' && static::shouldSkipDirtyLog($model)) {
                    return;
                }

                if (! Auth::check()) {
                    return;
                }

                $causer = Auth::user() ?? config('mongovity.causer_model')::query()->first();

                if (! $causer) {
                    return;
                }

                $activity = app(Mongovity::class)
                    ->by($causer)
                    ->on($model)
                    ->event($eventName);

                if (method_exists($model, 'getDescriptionForEvent')) {
                    $activity->log($model->getDescriptionForEvent($eventName));
                } else {
                    $activity->log();
                }
            });
        });
    }

    protected static function shouldSkipDirtyLog(Model $model): bool
    {
        if (! property_exists(static::class, 'logOnlyDirty')) {
            return false;
        }

        return (bool) static::$logOnlyDirty && $model->getDirty() === [];
    }

    public static function eventsToBeRecorded(): Collection
    {
        if (property_exists(new static(), 'recordEvents')) {
            return static::$recordEvents;
        }

        if (property_exists(new static(), 'loggableEvents')) {
            return static::$loggableEvents;
        }

        $events = collect([
            'created',
            'updated',
            'deleted',
        ]);

        if (collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)) {
            $events->push('restored');
        }

        return $events;
    }

    protected static function getModelAttributeJsonValue(Model $model, string $attribute)
    {
        $path = explode('->', $attribute);
        $modelAttribute = array_shift($path);
        $modelAttribute = collect($model->getAttribute($modelAttribute));

        return data_get($modelAttribute, implode('.', $path));
    }
}

<?php

namespace Rajtika\Mongovity\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Rajtika\Mongovity\Services\Mongovity;

trait ActivityTrait
{
    protected static function bootActivityTrait(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                if (! Auth::check()) {
                    return;
                }

                $causer = Auth::user() ?? config('mongovity.causer_model')::query()->first();

                if (! $causer) {
                    return;
                }

                app(Mongovity::class)
                    ->by($causer)
                    ->on($model)
                    ->event($eventName)
                    ->log();
            });
        });
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
            'deleted'
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

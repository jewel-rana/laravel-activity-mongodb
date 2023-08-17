<?php

namespace Rajtika\Mongovity\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Rajtika\Mongovity\Services\Mongovity;

trait ActivityTrait
{
    protected static function bootActivityTrait(): void
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            static::$eventName(function (Model $model) use ($eventName) {
                app(Mongovity::class)
                    ->by($model)
                    ->on($model)
                    ->event($eventName)
                    ->log();
            });
        });

        Log::info((new static())->dispatchesEvents);
    }

    public static function eventsToBeRecorded(): Collection
    {
        if (property_exists(new static(), 'logEvents')) {
            return static::$logEvents;
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

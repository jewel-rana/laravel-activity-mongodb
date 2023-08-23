<?php

namespace Rajtika\Mongovity\Services;

use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Traits\Macroable;
use Rajtika\Mongovity\Models\ActivityLog;
use ReflectionClass;

class Mongovity
{
    use Macroable;

    private ?Model $model = null;
    private ?string $event = null;
    private AuthManager $auth;
    private array $attributes = [];
    private ?Model $causedBy = null;
    private ?string $ip;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function get($limit = 15): LengthAwarePaginator
    {
        return App::make(ActivityLog::class, [
            'collection_name' => config('mongovity.collection_name', 'activity_logs'),
            'connection' => config('mongovity.connection_name', 'mongodb')
        ])
            ->latest()->paginate($limit);
    }

    public function by(Model $causedBy): Mongovity
    {
        $this->causedBy = $causedBy;
        return $this;
    }

    public function on(Model $performedOn): Mongovity
    {
        $this->model = $performedOn;
        return $this;
    }

    public function event($eventName = null): Mongovity
    {
        $this->event = $eventName;
        return $this;
    }

    public function attr(array $attributes = []): Mongovity
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function ip(string $ip): Mongovity
    {
        $this->ip = $ip;
        return $this;
    }

    public function log($message = null): void
    {
        try {
            App::make(ActivityLog::class, [
                'collection_name' => config('mongovity.collection_name', 'activity_logs'),
                'connection' => config('mongovity.connection_name', 'mongodb')
            ])
                ->create($this->getData($message));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    private function getData($message): array
    {
        return [
            'causer_id' => $this->getCauserId(),
            'causer_type' => $this->getCauserType(),
            'causer_name' => $this->causedBy->name ?? null,
            'causer_mobile' => $this->causedBy->mobile ?? null,
            'subject_id' => $this->model->id ?? null,
            'subject_type' => $this->model ? get_class($this->model) : null,
            'message' => $message ?? $this->getDefaultMessage(),
            'data' => $this->getAttr(),
            'log_name' => config('mongovity.log_name'),
            'ip' => $this->ip ?? request()->ip()
        ];
    }

    private function getCauserId()
    {
        return $this->causedBy->id ?? $this->auth->id ?? null;
    }

    private function getCauserType(): ?string
    {
        return $this->causedBy ? get_class($this->causedBy) : null;
    }

    private function getDefaultMessage()
    {
        return $this->model->defaultLogMessage ?? ucfirst($this->event) . " " . (new ReflectionClass($this->model))->getShortName();
    }

    private function getAttr(): array
    {
        $attr = [];
        if($this->event) {
            $attr['attributes'] = Arr::except(
                Arr::only(
                    $this->model->getAttributes() ?? $this->model->getDirty(),
                    $this->model->getFillable() ?? []
                ),
                $this->excepts()
            );
            if($this->event === 'updated') {
                $attr['attributes'] = Arr::except(
                    Arr::only(
                        $this->model->getRawOriginal(),
                        $this->model->getFillable() ?? []
                    ),
                    $this->excepts()
                );
                $attr['changes'] = Arr::except($this->model->getChanges(), $this->excepts());
            }
        } else {
            $attr['custom'] = $this->attributes;
        }
        return $attr;
    }

    private function excepts(): array
    {
        return [
            'password',
            'created_at',
            'updated_at',
            'deleted_at'
        ] + (array) $this->model->excludedFields ?? [];
    }
}

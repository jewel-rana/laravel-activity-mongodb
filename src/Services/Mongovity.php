<?php

namespace Rajtika\Mongovity\Services;

use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Traits\Macroable;
use Rajtika\Mongovity\Models\ActivityLog;
use ReflectionClass;

class Mongovity
{
    use Macroable;

    private Model $model;
    private ?string $event = null;
    private AuthManager $auth;
    private array $attributes = [];
    private Model $causedBy;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function test(): array
    {
        return ActivityLog::all()->toArray();
    }

    public function by(Model $causedBy): static
    {
        $this->causedBy = $causedBy;
        return $this;
    }

    public function on(Model $performedOn): static
    {
        $this->model = $performedOn;
        return $this;
    }

    public function event($eventName = null): static
    {
        $this->event = $eventName;
        return $this;
    }

    public function attr(array $attributes = []): static
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function log($message = null): void
    {
        try {
            ActivityLog::create($this->getData($message));
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    private function getData($message): array
    {
        return [
            'causer_id' => $this->getCauserId(),
            'causer_type' => $this->getCauserType(),
            'subject_id' => $this->model->id ?? null,
            'subject_type' => get_class($this->model) ?? null,
            'message' => $message ?? $this->getDefaultMessage(),
            'data' => $this->getAttr()
        ];
    }

    private function getCauserId()
    {
        return $this->causedBy->id ?? $this->auth->id ?? null;
    }

    private function getCauserType(): ?string
    {
        return get_class($this->causedBy) ?? get_class($this->auth) ?? null;
    }

    private function getDefaultMessage()
    {
        return $this->model->defaultLogMessage ?? ucfirst($this->event) . " " . (new ReflectionClass($this->model))->getShortName();
    }

    private function getAttr(): array
    {
        $attr = [];
        if($this->event) {
            if(property_exists($this->model, 'shouldLogDirty') && $this->model->shouldLogDirty) {
                $attr['attributes'] = $this->model->getDirty() ?? [];
            } else {
                $attr['attributes'] = $this->model->getRawOriginal();
            }
            if($this->event === 'updated') {
                $attr['changes'] = $this->model->getChanges();
            }
        } else {
            $attr['custom'] = $this->attributes;
        }
        return $attr;
    }
}

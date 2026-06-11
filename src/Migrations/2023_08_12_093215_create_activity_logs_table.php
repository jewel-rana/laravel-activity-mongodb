<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Rajtika\Mongovity\Constants\Mongovity;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        $collection = config(Mongovity::NAMESPACE . '.collection_name', 'activity_logs');

        if (! Schema::connection($this->connection)->hasTable($collection)) {
            Schema::connection($this->connection)
                ->create($collection, function ($collection) {
                    $collection->index('causer_id')->comment('Occurrence by');
                    $collection->string('causer_type');
                    $collection->string('causer_name');
                    $collection->index('causer_mobile');
                    $collection->index('subject_id')->comment('Modify on');
                    $collection->string('subject_type');
                    $collection->text('message');
                    $collection->index('ip');
                    $collection->json('data');
                    $collection->index('log_name')->default('default')->comment('Application / log name');
                });
        }
    }

    public function down(): void
    {
        Schema::connection(config(Mongovity::NAMESPACE . '.connection_name', 'mongodb'))
            ->dropIfExists(config(Mongovity::NAMESPACE . '.collection_name', 'activity_logs'));
    }
};

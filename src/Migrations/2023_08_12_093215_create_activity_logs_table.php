<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Rajtika\Mongovity\Constants\Mongovity;

class CreateActivityLogsTable extends Migration
{
    protected $connection = 'mongodb';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if(!Schema::connection($this->connection)
            ->hasTable('activity_logs')) {
            Schema::connection($this->connection)
                ->create(config('collection_name', 'activity_logs'), function ($collection) {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::connection(config(Mongovity::NAMESPACE . '.connection_name', 'mongodb'))
            ->dropIfExists('activity_logs');
    }
}

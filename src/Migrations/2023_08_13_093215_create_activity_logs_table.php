<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Rajtika\Mongovity\Constants\Mongovity;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::connection(config('mongovity.connection_name', 'mongodb'))
            ->create('activity_logs', function ($collection) {
                $collection->index('causer_id');
                $collection->index('causer_type');
                $collection->index('subject_id');
                $collection->index('subject_type');
                $collection->index('message');
                $collection->json('data');
            });
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

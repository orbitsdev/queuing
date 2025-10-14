<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
          $table->foreignId('previous_service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            // who forwarded
            $table->foreignId('forwarded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // details
            $table->boolean('is_forwarded')->default(false);
            $table->text('forward_reason')->nullable();

            $table->index(['is_forwarded', 'previous_service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
           $table->dropConstrainedForeignId('previous_service_id');
            $table->dropConstrainedForeignId('forwarded_by');
            $table->dropColumn(['is_forwarded', 'forward_reason']);
        });
    }
};

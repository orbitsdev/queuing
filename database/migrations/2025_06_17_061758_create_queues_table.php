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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('number');
            $table->string('ticket_number');
         
            $table->enum('status', ['waiting', 'called', 'serving', 'held', 'served', 'skipped', 'cancelled', 'expired', 'completed'])->default('waiting');
            $table->string('hold_reason')->nullable();
            $table->timestamps();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('serving_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('hold_started_at')->nullable();
            $table->timestamp('skipped_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->index('number');
            $table->index('ticket_number');
            $table->index('status');
            $table->index('service_id');
            $table->index('counter_id');
            $table->index('branch_id');
            $table->index('created_at');
            $table->index(['branch_id', 'status']);
            $table->index(['branch_id', 'created_at']);
            $table->index(['branch_id', 'service_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};

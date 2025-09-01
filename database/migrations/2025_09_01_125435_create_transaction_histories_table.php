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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('counter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ticket_number')->nullable();
            $table->integer('raw_number')->nullable();
            $table->string('action')->nullable();
            $table->string('status_before')->nullable();
            $table->string('status_after')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('transaction_time');
            $table->timestamps();

            // Add indexes for faster queries
            $table->index(['branch_id', 'transaction_time']);
            $table->index(['user_id', 'transaction_time']);
            $table->index(['counter_id', 'transaction_time']);
            $table->index(['service_id', 'transaction_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};

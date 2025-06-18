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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Ticket Settings
            $table->string('ticket_prefix')->default('QUE');
            $table->boolean('print_logo')->default(true);
            
            // Queue Settings
            $table->boolean('queue_reset_daily')->default(true);
            $table->time('queue_reset_time')->default('00:00');
            $table->string('default_break_message')->default('On break, please proceed to another counter.');
            
            $table->timestamps();
            
            // Each branch can only have one settings record
            $table->unique('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

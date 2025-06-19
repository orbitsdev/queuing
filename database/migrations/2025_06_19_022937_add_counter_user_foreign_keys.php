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
         Schema::table('counters', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('counter_id')
                  ->references('id')->on('counters')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['counter_id']);
        });
    }
};

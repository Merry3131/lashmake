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
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('specialist_id')->constrained('specialists')->onDelete('cascade');
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start');
            $table->time('break_end');
            $table->boolean('is_day_off')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};

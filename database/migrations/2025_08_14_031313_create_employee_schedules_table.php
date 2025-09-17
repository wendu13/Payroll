<?php

// database/migrations/2025_08_14_031313_create_employee_schedules_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('cutoff_schedule_id');
            $table->unsignedBigInteger('schedule_file_id'); // ADD THIS LINE - missing in your migration
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('type', ['regular', 'restday'])->default('regular'); // Changed 'work' to 'regular' to match your controller
            $table->text('remarks')->nullable(); // Add remarks field
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('cutoff_schedule_id')->references('id')->on('cutoff_schedules')->onDelete('cascade');
            $table->foreign('schedule_file_id')->references('id')->on('schedule_files')->onDelete('cascade'); // ADD THIS FOREIGN KEY
            
            // Add index for better performance
            $table->index(['employee_id', 'schedule_file_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('employee_schedules');
    }
};
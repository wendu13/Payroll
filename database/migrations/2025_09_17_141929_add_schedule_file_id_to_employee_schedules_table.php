<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_schedules', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('employee_schedules', 'schedule_file_id')) {
                $table->unsignedBigInteger('schedule_file_id')->after('cutoff_schedule_id');
                $table->foreign('schedule_file_id')->references('id')->on('schedule_files')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('employee_schedules', 'remarks')) {
                $table->text('remarks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('employee_schedules', 'schedule_file_id')) {
                $table->dropForeign(['schedule_file_id']);
                $table->dropColumn('schedule_file_id');
            }
            
            if (Schema::hasColumn('employee_schedules', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
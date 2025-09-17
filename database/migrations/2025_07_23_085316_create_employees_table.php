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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('department')->nullable();
            $table->string('employee_number')->unique();
            $table->string('position')->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('birthplace')->nullable();
            $table->date('birthdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->string('sss')->nullable();
            $table->string('philhealth')->nullable();
            $table->string('pagibig')->nullable();
            $table->string('tin')->nullable();
            $table->string('workday')->nullable();
            $table->string('restday')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->decimal('basic_rate', 10, 2)->nullable(); // monthly
            $table->decimal('allowance', 10, 2)->nullable();
            $table->decimal('other_pay', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};

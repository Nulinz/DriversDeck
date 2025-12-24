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
        Schema::create('permanent_jobs', function (Blueprint $table) {
            $table->id();

            $table->string('job_type');
            $table->string('veh_type');
            $table->integer('min_exp');
            $table->integer('max_exp');
            $table->string('job_location');
            $table->string('join_date');
            $table->string('min_salary');
            $table->string('max_salary');
            $table->string('accommodation');
            $table->string('food');
            $table->string('aggrement');
            $table->string('description');
            $table->string('a_years');
            
            $table->string('status')->default('pending');
            $table->integer('c_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permanent_jobs');
    }
};

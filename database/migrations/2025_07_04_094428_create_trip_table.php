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
        Schema::create('trip', function (Blueprint $table) {
            $table->id();

            $table->string('trip_img')->nullable();
            $table->string('st_loc')->nullable();
            $table->string('st_dest')->nullable();
            $table->string('st_city')->nullable();
            $table->string('end_city')->nullable();
            $table->string('st_cord')->nullable();
            $table->string('end_cord')->nullable();
            $table->string('dest_cord')->nullable();
            $table->string('title')->nullable();
            $table->string('con_number')->nullable();
            $table->string('alter_number')->nullable();
            $table->date('st_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('st_time')->nullable();
            $table->string('no_days')->nullable();
            $table->string('veh_type')->nullable();
            $table->string('d_type')->nullable();
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
        Schema::dropIfExists('trip');
    }
};

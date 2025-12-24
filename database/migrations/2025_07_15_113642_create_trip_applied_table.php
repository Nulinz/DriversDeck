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
        Schema::create('trip_applied', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('d_id');

            $table->string('salary_perday')->nullable();
            $table->string('wait_charge')->nullable();
            $table->string('food')->nullable();
            $table->string('status')->nullable();
            $table->string('report_sts')->nullable();
            $table->string('c_by')->nullable();


            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('start_loc')->nullable();
            $table->string('end_loc')->nullable();
            $table->string('crnt_loc')->nullable();
            $table->integer('trip_code')->nullable();

            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();


            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trip');
            $table->foreign('d_id')->references('id')->on('driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_applied');
    }
};

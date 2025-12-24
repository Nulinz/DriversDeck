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
        Schema::create('sub_applied', function (Blueprint $table) {
            $table->id();

            
            $table->unsignedBigInteger('p_id');  // permanent_jobs id
            $table->unsignedBigInteger('d_id');

            $table->string('status')->nullable();
            $table->string('c_by')->nullable();

            $table->timestamps();

            $table->foreign('p_id')->references('id')->on('permanent_jobs');
            $table->foreign('d_id')->references('id')->on('driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_applied');
    }
};

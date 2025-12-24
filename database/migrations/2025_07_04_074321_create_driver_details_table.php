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
        Schema::create('driver_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('d_id'); // foreign key
            $table->text('c_ad')->nullable();
            $table->text('c_city')->nullable();
            $table->text('c_state')->nullable();
            $table->integer('c_pin');
            $table->text('about')->nullable();
            $table->string('exp_year')->nullable();
            $table->string('exp_mon')->nullable();
            $table->string('p_com_name')->nullable();
            $table->date('rel_date')->nullable();
            $table->string('com_location')->nullable();
            $table->string('contact_number')->nullable();
            $table->integer('current_salary')->nullable();
            $table->string('pf')->nullable(); // assuming true/false
            $table->integer('expert_salary')->nullable();
            $table->string('job_loc');
            $table->text('agreement')->nullable(); // or use string if it's a file name
            $table->text('years')->nullable();
            $table->timestamps();

            // Optional: foreign key constraint if d_id refers to drivers table
            $table->foreign('d_id')->references('id')->on('driver')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_details');
    }
};

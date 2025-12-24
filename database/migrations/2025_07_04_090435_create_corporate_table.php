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
        Schema::create('corporate', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['owner', 'corporate']);
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->string('gender')->nullable();    //kaviya
            $table->string('ref_code')->nullable();
            $table->string('c_type')->nullable();
            $table->string('contact')->nullable();
            $table->string('mail')->nullable();
            $table->string('c_name')->nullable();
            $table->string('c_num')->nullable();
            $table->integer('otp')->nullable();
            $table->string('a_num')->nullable();
            $table->string('c_mail')->nullable();
            $table->string('ad_1')->nullable();
            $table->string('ad_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin')->nullable();
            $table->string('pan')->nullable();
            $table->string('gst')->nullable();
            $table->string('no_veh')->nullable();
            $table->string('no_driver')->nullable();
            $table->string('no_vac')->nullable();
            $table->string('subscription')->nullable();
            $table->text('logo')->nullable();
            $table->string('token')->nullable();
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
        Schema::dropIfExists('corporate');
    }
};

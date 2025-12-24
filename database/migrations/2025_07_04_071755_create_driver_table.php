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
        Schema::create('driver', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['acting', 'permanent']);
            $table->string('name')->nullable(); // name
            $table->string('phone')->nullable(); // phone
            $table->integer('otp'); // OTP
            $table->string('l_no')->nullable(); // license number
            $table->string('location')->nullable(); // location
            $table->text('img')->nullable(); // image (can be null)
            $table->string('subscription', 100)->nullable(); // subscription
            $table->string('gender')->nullable(); // gender
            $table->string('marital_status')->nullable(); // marital status
            $table->string('b_group')->nullable(); // blood group
            $table->string('ad_num')->nullable(); // Aadhar number
            $table->string('ref_code')->nullable();
            $table->string('token')->nullable();
            $table->string('status')->default('pending'); // status
            $table->integer('c_by')->nullable(); // vehicle type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver');
    }
};

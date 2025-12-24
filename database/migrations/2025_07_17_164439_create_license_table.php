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
      Schema::create('license', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('d_id');
    $table->string('cof')->nullable();
    $table->string('l_no')->unique();    // Keep as not nullable if it's required
    $table->string('l_img')->nullable();
    $table->string('aadhaar')->nullable();
    $table->string('aadhaar_img')->nullable();
    $table->date('dob')->nullable();  // Changed to nullable
    $table->string('cov')->nullable();
    $table->string('issued_rto')->nullable();
    $table->date('date_of_issue')->nullable();  // Changed to nullable
    $table->date('v_from')->nullable();  // Changed to nullable
    $table->date('v_to')->nullable();  // Changed to nullable
    $table->string('batch_no')->nullable();
    $table->date('batch_issue_date')->nullable();  // Changed to nullable
    $table->string('batch_issued_by')->nullable();
    $table->string('card_serial_no')->nullable();
    $table->text('ad_1')->nullable();
    $table->text('ad_2')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('status')->default('active');
    $table->integer('c_by')->nullable();
    $table->timestamps();

    $table->foreign('d_id')->references('id')->on('driver')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license');
    }
};

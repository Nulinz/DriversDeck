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
        Schema::create('bank_withdraw', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['acting', 'permanent', 'corprate', 'owner']);
            $table->integer('d_id')->nullable(); // driver ID
            $table->integer('amt')->nullable(); // driver ID
            $table->string('name')->nullable(); // account holder name
            $table->string('bank')->nullable(); // bank name
            $table->string('branch')->nullable(); // branch name
            $table->string('ifsc')->nullable(); // IFSC code
            $table->string('acc_no')->nullable(); // account number
            $table->string('upi_name')->nullable(); // UPI name
            $table->string('upi_id')->nullable(); // UPI ID

            $table->string('status')->default('pending');
            $table->integer('c_by')->nullable();
            $table->timestamps();


            // $table->foreign('d_id')->references('id')->on('driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_withdraw');
    }
};

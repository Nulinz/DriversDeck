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
        Schema::create('subscription', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('f_id');
            // $table->enum('type', ['owner', 'corporate', 'acting', 'permanent']);
            // ALTER TABLE subscription MODIFY COLUMN type VARCHAR(20); (testing) //kaviya
            // $table->enum('plan', ['3', '6', '9', '12']);

            $table->string('type', 20); 
            $table->string('plan', 10);

            $table->string('t_id')->nullable();
            $table->string('amount')->nullable();
            $table->string('paid_sts')->nullable();
            $table->string('exp_date')->nullable();
            $table->string('status')->nullable();
            $table->string('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription');
    }
};

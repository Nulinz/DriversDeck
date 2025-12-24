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
        Schema::create('referal', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('ref_type');
            $table->integer('ref_by');
            $table->string('f_type');
            $table->integer('f_id');
            $table->integer('amt');
            $table->string('status')->default('active');
            $table->integer('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referal');
    }
};

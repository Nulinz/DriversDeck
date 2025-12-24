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
        Schema::create('cancel_req', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trip_id');
            $table->enum('type', ['owner', 'acting']);      
            $table->text('remarks')->nullable(); 
            $table->text('reason')->nullable();
            $table->text('status')->nullable();  
$table->string('c_type')->nullable();     
      
            $table->integer('c_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancel_req');
    }
};

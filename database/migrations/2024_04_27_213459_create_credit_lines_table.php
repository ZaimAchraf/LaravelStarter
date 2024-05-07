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
        Schema::create('credit_lines', function (Blueprint $table) {
            $table->id();
            $table->double("amount");
            $table->string("comment")->nullable();
            $table->string("document")->nullable();
            $table->foreignId('credit_id')->constrained('credits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_lines');
    }
};

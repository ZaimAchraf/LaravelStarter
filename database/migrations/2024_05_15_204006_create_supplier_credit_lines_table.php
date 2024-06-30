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
        Schema::create('supplier_credit_lines', function (Blueprint $table) {
            $table->id();
            $table->double("amount");
            $table->string("comment")->nullable();
            $table->string("document")->nullable();
            $table->foreignId('supplier_credit_id')->constrained('supplier_credits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_credit_lines');
    }
};

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
        Schema::create('quotation_lines', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->string("reference")->nullable();
            $table->integer("quantity")->nullable();
            $table->double("price");
            $table->integer("TVA")->default(0);
            $table->string("state")->nullable();
            $table->string("type");
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->double("purchase_price");
            $table->foreignId('provider_id')->constrained('providers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_lines');
    }
};

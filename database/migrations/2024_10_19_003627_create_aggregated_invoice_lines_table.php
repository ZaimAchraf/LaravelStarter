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
        Schema::create('aggregated_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->string("reference")->nullable();
            $table->integer("quantity");
            $table->string("type");
            $table->double("price");
            $table->integer("TVA")->default(0);
            $table->string("state")->nullable();
            $table->foreignId('invoice_id')->constrained('aggregated_invoices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggregated_invoice_lines');
    }
};

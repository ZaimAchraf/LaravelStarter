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
        Schema::create('supplier_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('Qte')->default(0);
            $table->double('price');
            $table->foreignId('c_product_id')->constrained('c_products');
            $table->foreignId('invoice_id')->constrained('supplier_invoices');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoice_lines');
    }
};

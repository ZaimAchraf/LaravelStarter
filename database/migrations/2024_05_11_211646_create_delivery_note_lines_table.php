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
        Schema::create('delivery_note_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('Qte')->default(0);
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('delivery_note_id')->constrained('delivery_notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_note_lines');
    }
};

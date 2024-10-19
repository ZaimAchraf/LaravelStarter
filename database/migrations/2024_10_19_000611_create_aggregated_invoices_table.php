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
        Schema::create('aggregated_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('title')->nullable();
            $table->string('yearly_counter')->nullable();
            $table->string('client');
            $table->double('total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggregated_invoices');
    }
};

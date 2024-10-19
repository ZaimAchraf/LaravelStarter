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
        Schema::create('folder_documents', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('label');
            $table->string('name');
            $table->foreignId('folder_id')->constrained('folders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folder_documents');
    }
};

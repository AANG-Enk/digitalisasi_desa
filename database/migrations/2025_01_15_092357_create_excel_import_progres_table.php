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
        Schema::create('excel_import_progres', function (Blueprint $table) {
            $table->id();
            $table->char('key',10)->unique();
            $table->unsignedInteger('total_rows')->default(0); // Total baris
            $table->unsignedInteger('processed_rows')->default(0); // Baris yang diproses
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_import_progres');
    }
};

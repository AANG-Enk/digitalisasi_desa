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
        Schema::create('surat_tujuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_id');
            $table->string('nama',50);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('surat_id','foreign_key_surat_surat_tujuans')->references('id')->on('surats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_tujuans', function (Blueprint $table) {
            $table->dropForeign('foreign_key_surat_surat_tujuans');
        });
        Schema::dropIfExists('surat_tujuans');
    }
};

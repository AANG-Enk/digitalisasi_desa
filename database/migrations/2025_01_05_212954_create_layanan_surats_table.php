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
        Schema::create('layanan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_tujuan_id');
            $table->foreignId('user_id');
            $table->string('nomor_surat_rt')->nullable();
            $table->string('nomor_surat_rw')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('nama_rt')->nullable();
            $table->string('nama_rw')->nullable();
            $table->date('tanggal_tanda_tangan')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('surat_tujuan_id','foreign_key_surat_tujuan_layanan_surats')->references('id')->on('surat_tujuans')->onDelete('cascade');
            $table->foreign('user_id','foreign_key_user_layanan_surats')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanan_surats', function (Blueprint $table) {
            $table->dropForeign('foreign_key_surat_tujuan_layanan_surats');
            $table->dropForeign('foreign_key_user_layanan_surats');
        });
        Schema::dropIfExists('layanan_surats');
    }
};

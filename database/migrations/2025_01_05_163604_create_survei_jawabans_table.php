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
        Schema::create('survei_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('survei_pertanyaan_id');
            $table->text('jawaban')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('survei_pertanyaan_id','foreign_key_survei_pertanyaan_survei_jawabans')->references('id')->on('survei_pertanyaans')->onDelete('cascade');
            $table->foreign('user_id','foreign_key_user_survei_jawaban')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei_jawabans', function (Blueprint $table) {
            $table->dropForeign('foreign_key_survei_pertanyaan_survei_jawabans');
            $table->dropForeign('foreign_key_user_survei_jawaban');
        });
        Schema::dropIfExists('survei_jawabans');
    }
};

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
        Schema::create('survei_pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_id');
            $table->string('pertanyaan',255);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('survei_id','foreign_key_survei_survei_pertanyaans')->references('id')->on('surveys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survei_pertanyaans', function (Blueprint $table) {
            $table->dropForeign('foreign_key_survei_survei_pertanyaans');
        });
        Schema::dropIfExists('survei_pertanyaans');
    }
};

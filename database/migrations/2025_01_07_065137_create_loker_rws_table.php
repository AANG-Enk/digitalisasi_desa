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
        Schema::create('loker_rws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('judul',255);
            $table->string('slug',255)->unique();
            $table->string('perusahaan',255);
            $table->string('posisi',255);
            $table->text('deskripsi');
            $table->string('image')->nullable();
            $table->string('hubungi',15);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id','foreign_key_user_loker_rws')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loker_rws', function (Blueprint $table) {
            $table->dropForeign('foreign_key_user_loker_rws');
        });
        Schema::dropIfExists('loker_rws');
    }
};

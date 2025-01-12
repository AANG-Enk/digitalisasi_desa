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
        Schema::create('iklan_rws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('judul',255);
            $table->string('slug',255)->unique();
            $table->string('image',255);
            $table->text('deskripsi');
            $table->date('start');
            $table->date('end');
            $table->string('link')->nullable();
            $table->string('hubungi',15)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id','foreign_key_user_iklan_rws')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklan_rws', function (Blueprint $table) {
            $table->dropForeign('foreign_key_user_iklan_rws');
        });
        Schema::dropIfExists('iklan_rws');
    }
};

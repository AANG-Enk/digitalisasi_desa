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
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('judul',255);
            $table->string('slug',255)->unique();
            $table->text('deskripsi');
            $table->string('image',255);
            $table->date('published_at');
            $table->enum('bagian',['Warga','Pengurus'])->default('Warga');
            $table->integer('dilihat')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id','foreign_key_user_forums')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forums', function (Blueprint $table) {
            $table->dropForeign('foreign_key_user_forums');
        });
        Schema::dropIfExists('forums');
    }
};

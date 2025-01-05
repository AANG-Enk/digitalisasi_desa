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
        Schema::create('tanya_rws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->text('warga_text')->nullable();
            $table->text('rw_text')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id','foreign_key_user_tanya_rws')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tanya_rws', function (Blueprint $table) {
            $table->dropForeign('foreign_key_user_tanya_rws');
        });
        Schema::dropIfExists('tanya_rws');
    }
};

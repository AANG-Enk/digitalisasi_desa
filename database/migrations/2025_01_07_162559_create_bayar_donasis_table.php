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
        Schema::create('bayar_donasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donasi_id');
            $table->foreignId('user_id');
            $table->decimal('nominal', 20, 2);
            $table->enum('pembayaran',['Tunai','Non Tunai']);
            $table->text('keterangan')->nullable();
            $table->boolean('is_verified')->nullable();
            $table->string('file',255)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('donasi_id','foreign_key_donasi_bayar_donasis')->references('id')->on('donasis')->onDelete('cascade');
            $table->foreign('user_id','foreign_key_user_bayar_donasis')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bayar_donasis', function (Blueprint $table) {
            $table->dropForeign('foreign_key_donasi_bayar_donasis');
            $table->dropForeign('foreign_key_user_bayar_donasis');
        });
        Schema::dropIfExists('bayar_donasis');
    }
};

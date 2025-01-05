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
        Schema::create('r_t_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rw_id');
            $table->string('nama',5);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->foreign('rw_id','foreign_key_rw_r_t_s')->references('id')->on('r_w_s')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r_t_s', function (Blueprint $table) {
            $table->dropForeign('foreign_key_rw_r_t_s');
        });
        Schema::dropIfExists('r_t_s');
    }
};

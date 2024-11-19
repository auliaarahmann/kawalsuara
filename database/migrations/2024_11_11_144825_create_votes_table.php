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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['verified', 'unverified'])->default('unverified');
            $table->unsignedBigInteger('kecamatan_id');
            $table->unsignedBigInteger('kelurahan_id');
            $table->unsignedBigInteger('tps_id');
            $table->bigInteger('paslon_1_vote');
            $table->bigInteger('paslon_2_vote');
            $table->bigInteger('paslon_3_vote');
            $table->string('foto_c1_plano');
            $table->timestamps();
    
            // Define foreign keys
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('cascade');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->onDelete('cascade');
            $table->foreign('tps_id')->references('id')->on('tps')->onDelete('cascade');
    
            // Add unique constraint
            $table->unique(['kecamatan_id', 'kelurahan_id', 'tps_id']);           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};

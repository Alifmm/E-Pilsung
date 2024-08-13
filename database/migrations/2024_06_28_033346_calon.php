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
        Schema::create('calons', function (Blueprint $table) {
            $table->id('idcalon');
            $table->unsignedBigInteger('idketua')->unique();
            $table->foreign('idketua')->references('id')->on('users')->onDelete('cascade');
            $table->string("wajahketua");
            $table->unsignedBigInteger('idsekretaris')->unique();
            $table->foreign('idsekretaris')->references('id')->on('users')->onDelete('cascade');
            $table->string("wajahsekretaris");
            $table->unsignedBigInteger('idbendahara')->unique();
            $table->foreign('idbendahara')->references('id')->on('users')->onDelete('cascade');
            $table->string("wajahbendahara");
            $table->text("visi");
            $table->text("misi");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calons');
    }
};

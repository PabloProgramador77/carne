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
        Schema::create('corte_has_abonos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idCorte')->unsigned();
            $table->bigInteger('idAbono')->unsigned();
            $table->foreign('idCorte')->references('id')->on('cortes')->onDelete('cascade');
            $table->foreign('idAbono')->references('id')->on('abonos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_has_abonos');
    }
};

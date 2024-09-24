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
        Schema::create('corte_has_pedidos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idCorte')->unsigned();
            $table->bigInteger('idPedido')->unsigned();
            $table->timestamps();

            $table->foreign('idCorte')->references('id')->on('cortes')->onDelete('cascade');
            $table->foreign('idPedido')->references('id')->on('pedidos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_has_pedidos');
    }
};

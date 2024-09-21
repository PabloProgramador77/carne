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
        Schema::create('pedido_has_productos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idPedido')->unsigned();
            $table->bigInteger('idClienteHasProducto')->unsigned();
            $table->decimal('cantidad', 10, 2);
            $table->decimal('monto', 10, 2);
            $table->timestamps();

            $table->foreign('idPedido')->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreign('idClienteHasProducto')->references('id')->on('cliente_has_productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_has_productos');
    }
};

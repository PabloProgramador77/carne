<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoHasProducto extends Model
{
    use HasFactory;

    protected $table = 'pedido_has_productos';

    protected $fillable = [

        'idPedido',
        'idClienteHasProducto',
        'cantidad',
        'monto',

    ];
}

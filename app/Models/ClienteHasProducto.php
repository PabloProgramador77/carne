<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteHasProducto extends Model
{
    use HasFactory;

    protected $table = 'cliente_has_productos';

    protected $fillable = [
        
        'idCliente',
        'idProducto',
        'precio',

    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [

        'total',
        'nota',
        'idCliente',

    ];

    public function cliente(){

        return $this->hasOne( Cliente::class, 'id', 'idCliente' );

    }

}

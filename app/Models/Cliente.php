<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [

        'nombre',
        'telefono',
        'domicilio',
        'deuda',

    ];

    public function productos(){

        return $this->belongsToMany( Producto::class, 'cliente_has_productos', 'idCliente', 'idProducto' )->withPivot('precio');

    }

    public function pedido(){

        return $this->belongsTo( Pedido::class, 'idCliente' );
        
    }
}

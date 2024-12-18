<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    use HasFactory;

    protected $table = 'cortes';

    protected $fillable = [

        'total',
        'efectivo',

    ];

    public function pedidos(){

        return $this->belongsToMany( Pedido::class, 'corte_has_pedidos', 'idCorte', 'idPedido' );
        
    }

    public function abonos(){

        return $this->belongsToMany( Abono::class, 'corte_has_abonos', 'idCorte', 'idAbono' );

    }
}

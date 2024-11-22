<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;

    protected $table = 'abonos';

    protected $fillable = [

        'monto',
        'nota',
        'estado',
        'idCliente',

    ];

    public function cliente(){

        return $this->hasOne( Cliente::class, 'id', 'idCliente' );
        
    }

    public function corte(){

        return $this->belongsTo( Corte::class, 'corte_has_abonos', 'idAbono', 'idCorte' );
        
    }
}

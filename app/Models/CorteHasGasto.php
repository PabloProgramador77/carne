<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorteHasGasto extends Model
{
    use HasFactory;

    protected $table = 'corte_has_gastos';

    protected $fillable = [

        'idCorte',
        'idGasto',

    ];

    public function corte(){

        return $this->hasOne( Corte::class, 'id', 'idCorte');

    }

    public function gastos(){

        return $this->belongsToMany( Gasto::class, 'corte_has_gastos', 'id', 'idGasto');
        
    }
}

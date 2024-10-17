<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $fillable = [

        'monto',
        'descripcion',
        'idCaja',

    ];

    public function caja(){

        return $this->hasOne( Caja::class, 'id', 'idCaja' );
        
    }
}

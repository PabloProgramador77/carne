<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    protected $fillable = [

        'monto',
        'nota',
        'idCliente',

    ];

    public function cliente(){

        return $this->hasOne( Cliente::class, 'id', 'idCliente');
        
    }
}

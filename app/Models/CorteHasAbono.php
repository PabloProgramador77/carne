<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorteHasAbono extends Model
{
    protected $table = 'corte_has_abonos';

    protected $fillable = [

        'idCorte',
        'idAbono',

    ];

}

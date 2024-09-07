<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operadores extends Model
{
    use HasFactory;

    protected $fillable = [
        'R_ID',
        'R_Color',
        'R_Numero',
        'R_Letra',
        'R_Status',


    ];

    protected $table = 'rutas';
    protected $primaryKey = 'R_ID';
}

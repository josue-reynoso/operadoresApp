<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operadores extends Model
{
    use HasFactory;

    protected $fillable = [
        'OP_Name',
        'OP_APE',
        'OP_APEM',
        'OP_Fch_Nac',
        'OP_Address',
        'OP_Cel',
        'OP_Email',
        'OP_Status',


    ];

    protected $table = 'operadores';
    protected $primaryKey = 'OP_ID';
}

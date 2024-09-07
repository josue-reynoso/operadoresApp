<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'COM_ID',
        'COM_OP_ID',
        'COM_DES',
        'COM_DATE',


    ];

    protected $table = 'comentarios';
    protected $primaryKey = 'COM_ID';
}

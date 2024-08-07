<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biblioteca extends Model
{
    use HasFactory;

    protected $table = 'tbl_biblioteca';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'descripcion',
        'archivo',
        'created_at',
        'updated_at ', 
    ];

    protected $casts = [

    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    use HasFactory;

    protected $table = 'tbl_laboratorio';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'descripcion',
        'created_at',
        'updated_at ', 
    ];

    protected $casts = [

    ];
}

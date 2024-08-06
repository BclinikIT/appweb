<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metabogramas extends Model
{
    use HasFactory;

    protected $table = 'tbl_metabograma';

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'tipo',
        'created_at',
        'updated_at ', 
    ];
    protected $casts = [

    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imc_Formulario extends Model
{
    use HasFactory;
    protected $table = 'tbl_imc_formulario';

    protected $fillable = [
        'nombre',
        'apellido',
        'edad',
        'genero',
        'peso_en_libras',
        'altura_en_cms',
        'correo',
        'telefono',
        'categoria',
        'imc',
        'fecha',
        'hora',
        'form_id'
    ];
}

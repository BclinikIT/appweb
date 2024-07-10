<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioImc extends Model
{
    use HasFactory;

    protected $table = 'tbl_imc_formulario'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
        'apellido',
        'edad',
        'genero',
        'peso_en_libras',
        'altura_en_cms',
        'correo',
        'telefono',
        'fecha',
        'hora',
        'form_id',
        'created_at',
        'updated_at ',
    ];

    protected $casts = [
       
    ];
}

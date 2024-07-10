<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioImcInvitados extends Model
{
    use HasFactory;

    protected $table = 'tbl_imc_invitacion'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
        'apellido',
        'nombre_invitado',
        'apellido_invitado',
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


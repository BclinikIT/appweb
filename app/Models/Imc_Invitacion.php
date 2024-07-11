<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imc_Invitacion extends Model
{
    use HasFactory;
    protected $table = 'tbl_imc_invitacion';

    protected $fillable = [
        'nombre',
        'apellido',
        'nombre_invitado',
        'apellido_invitado',
        'correo',
        'telefono',
        'fecha',
        'hora',
        'form_id'

    ];
}

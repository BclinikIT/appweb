<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cribado_Form_Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'tbl_cribado_form_cotizacion';

    protected $fillable = [
        'nombre_de_la_empresa',
        'direccion',
        'cantidad_de_colaboradores_en_total',
        'nombre_de_quien_solicita',
        'puesto_en_la_empresa',
        'telefono_directo_movil',
        'email',
        'date',
        'time',
        'page_url',
        'user_agent',
        'remote_ip',
        'powered_by',
        'form_id',
        'form_name',
    ];



}

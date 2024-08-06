<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaCribado extends Model
{
    use HasFactory;

    // La tabla asociada al modelo.
    protected $table = 'tbl_cribado_form_encuesta';

    // Los atributos que son asignables en masa.
    protected $fillable = [
        'nombre',
        'apellido',
        'edad',
        'telefono_personal',
        'correo',
        'empresa',
        'sede',
        'genero',
        'peso_en_libras',
        'altura_en_cms',
        'cintura_en_cms',
        'imc', // Nuevo campo
        'categoria', // Nuevo campo
        'habitos', // Nuevo campo
        'agua_diaria',
        'horarios_refacciones_comidas',
        'porciones_pequenas',
        'separar_combinar_alimentos',
        'rutina_ejercicio',
        'vivir_saludable',
        'insulina_alta',
        'resistencia_insulina',
        'elevaciones_azucar_embarazo',
        'sindrome_ovarios_poliquistico',
        'sobrepeso',
        'diabetes_embarazo_hijo',
        'ejercicio_regular',
        'ovario_poliquistico',
        'padre',
        'madre',
        'hermanos',
        'tios_paternos',
        'tios_maternos',
        'abuelos_maternos',
        'abuelos_paternos',
        'antecedentes_porcentaje', // Nuevo campo
        'factores_porcentaje', // Nuevo campo
        'hereditarios_porcentaje', // Nuevo campo
        'porcentaje_promedio_final', // Nuevo campo
    ];

    // Los atributos que deberían ser tratados como fechas.
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}

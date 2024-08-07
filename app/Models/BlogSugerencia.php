<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogSugerencia extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla
    protected $table = 'tbl_blog_sugerencias';

    // Definir los campos que pueden ser llenados masivamente
    protected $fillable = [
        'nombre', 'telefono', 'correo', 'mensaje', 'archivo',
        'page_url', 'user_agent', 'remote_ip', 'powered_by'
    ];
}

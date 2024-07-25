<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EncuestaCribado;


class EncuestaCribadoController extends Controller
{
    public function index(){
        $datos = EncuestaCribado::orderBy('created_at', 'desc')->get();
        return inertia('Encuesta_cribado/Lista', ['datos' => $datos]);
    }
}

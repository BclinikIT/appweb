<?php

namespace App\Http\Controllers;

use App\Models\FormularioImcInvitados;
use Illuminate\Http\Request;

class FormularioImcInvitadosController extends Controller
{
    public function index(){
        $datos = FormularioImcInvitados::orderBy('fecha', 'desc')
                                    ->orderBy('hora', 'desc')->get();
        return inertia('Formulario_imc/Lista_invitado', ['datos' => $datos]);
    }
}

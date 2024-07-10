<?php

namespace App\Http\Controllers;
// use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\FormularioImc;

class FormularioImcController extends Controller
{
    public function index(){
        $datos = FormularioImc::all();
        return inertia('Formulario_imc/Lista', ['datos' => $datos]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cribado_Form_Cotizacion;
use Illuminate\Http\Request;

class CribadoController extends Controller
{
    public function index(){
        $datos =Cribado_Form_Cotizacion::all();
        return inertia('Cribado/cribado', ['datos' => $datos]);
    }

}

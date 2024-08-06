<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    public function index(){

        $datos = Laboratorio::orderBy('created_at','desc')
                            ->get();
        return inertia('Laboratorio/List_laboratorio', ['datos'=>$datos]);
    }
}

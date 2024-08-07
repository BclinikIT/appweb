<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use Illuminate\Http\Request;

class BibliotecaController extends Controller
{
    public function index(){
        $datos = Biblioteca::orderBy('created_at','desc')
                            ->get();
        return inertia('Biblioteca/List_biblioteca', ['datos' => $datos]);
    }
}

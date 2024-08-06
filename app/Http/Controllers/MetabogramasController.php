<?php

namespace App\Http\Controllers;

use App\Models\Metabogramas;
use Illuminate\Http\Request;

class MetabogramasController extends Controller
{
    public function index(){
        $datos = Metabogramas::where('tipo', 1)
                            ->orderBy('created_at','desc')
                            ->get();
        return inertia('Metabogramas/List_metabogramas', ['datos'=>$datos]);
    }


    public function view_plus(){
        $datos = Metabogramas::where('tipo', 2)
                            ->orderBy('created_at','desc')
                            ->get();
        return inertia('Metabogramas/List_metabogramas', ['datos'=>$datos]);

    }

    public function view_pro(){
        $datos = Metabogramas::where('tipo', 3)
                            ->orderBy('created_at','desc')
                            ->get();
        return inertia('Metabogramas/List_metabogramas', ['datos'=>$datos]);
    }

}

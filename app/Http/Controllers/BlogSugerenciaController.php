<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogSugerencia;

class BlogSugerenciaController extends Controller
{
    public function index()
    {

        $datos = BlogSugerencia::orderBy('created_at','desc')
                            ->get();
                            return inertia('Blog/list', ['datos'=>$datos]);
    }


}

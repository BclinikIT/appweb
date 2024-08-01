<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $data = [
            // Aquí puedes pasar datos al archivo Blade si es necesario
            'date' => date('Y-m-d'),
            'nombre' => 'Juan Pérez',
            'apellido' => 'Empresa S.A. de C.V.',
            'imc'=> '25.5',
            'categoria'=> 'Sobrepeso',
            // Otros datos...
        ];

        $pdf = PDF::loadView('pdf.cribado_encuesta', $data);

        //return $pdf->download('cribado_cotizacion.pdf');
        return $pdf->stream('cribado_encuesta.pdf');
    }
}

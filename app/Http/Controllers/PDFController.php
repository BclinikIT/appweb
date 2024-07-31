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
            'nombre_de_quien_solicita' => 'Juan Pérez',
            'nombre_de_la_empresa' => 'Empresa S.A. de C.V.',
            'puesto_en_la_empresa' => 'Gerente de Ventas',
            // Otros datos...
        ];

        $pdf = PDF::loadView('pdf.cribado_cotizacion', $data);

        //return $pdf->download('cribado_cotizacion.pdf');
        return $pdf->stream('cribado_cotizacion.pdf');
    }
}

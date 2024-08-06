<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use App\Models\Metabogramas;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use PDF;



class MetabogramasWebhookController extends Controller
{
   
    public function handleMetabogramas(Request $request)
    {
        try {

            // Recoger datos del request usando el método only()
            $data = $request->only([
                'Nombre',
                'Apellido',
                'Teléfono',
                'Correo',
                'Tipo',
                'Date',
                'Time',
                'Page_URL',
                'User_Agent',
                'Remote_IP',
                'Powered_by',
                'form_id',
                'form_name'
            ]);

            // Preparar datos para guardar
            $dataToLog = [
                'nombre' => $data['Nombre'],
                'apellido' => $data['Apellido'],
                'correo' => $data['Correo'],
                'telefono' => $data['Teléfono'],
                'tipo' => $data['Tipo'],
            ];
            
            Metabogramas::create($dataToLog);
            return response()->json(['message' => 'Datos recibidos y almacenados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
    
      


}

<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LaboratorioWebhookController extends Controller
{
    public function handleLaboratorio(Request $request)
    {
        try {

            // Log::info('Request data:', $request->all());
            // Recoger datos del request usando el método only()
            $data = $request->only([
                'Nombre_Completo',
                'Telefono',
                'Correo',
                'Mensaje',
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
                'nombre' => $data['Nombre_Completo'],
                'apellido' => '',
                'correo' => $data['Correo'],
                'telefono' => $data['Telefono'],
                'descripcion' => $data['Mensaje'],
            ];
            
            Laboratorio::create($dataToLog);
            return response()->json(['message' => 'Datos recibidos y almacenados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
}

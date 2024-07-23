<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cribado_Form_Cotizacion;

class CribadoWebhookController extends Controller
{
    public function handleCribadoCotizacion(Request $request)
    {
        $nombre_de_la_empresa = $request->input('Nombre_de_la_empresa');
        $direccion = $request->input('Dirección_');
        $cantidad_de_colaboradores = $request->input('Cantidad_de_colaboradores_en_total');
        $nombre_de_quien_solicita = $request->input('Nombre_de_quien_solicita');
        $puesto_en_la_empresa = $request->input('Puesto_en_la_empresa');
        $telefono_directo_movil = $request->input('Teléfono_directo_–_móvil');
        $email = $request->input('Email');
        $date = $request->input('Date');
        $time = $request->input('Time');
        $page_url = $request->input('Page_URL');
        $user_agent = $request->input('User_Agent');
        $remote_ip = $request->input('Remote_IP');
        $powered_by = $request->input('Powered_by');
        $form_id = $request->input('form_id');
        $form_name = $request->input('form_name');

        $dataToInsert = [
            'nombre_de_la_empresa' => $nombre_de_la_empresa,
            'direccion' => $direccion,
            'cantidad_de_colaboradores_en_total' => $cantidad_de_colaboradores,
            'nombre_de_quien_solicita' => $nombre_de_quien_solicita,
            'puesto_en_la_empresa' => $puesto_en_la_empresa,
            'telefono_directo_movil' => $telefono_directo_movil,
            'email' => $email,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'page_url' => $page_url,
            'user_agent' => $user_agent,
            'remote_ip' => $remote_ip,
            'powered_by' => $powered_by,
            'form_id' => $form_id,
            'form_name' => $form_name,
        ];

        // Log de los datos específicos
        Log::info('Datos específicos recibidos del webhook:', $dataToInsert);

        // Crear un nuevo registro en la base de datos
        Cribado_Form_Cotizacion::create($dataToInsert);

        return response()->json(['message' => 'Datos recibidos y almacenados correctamente'], 200);
    }
}

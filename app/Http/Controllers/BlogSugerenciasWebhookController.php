<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlogSugerenciasWebhookController extends Controller
{
    public function handleBlogSugerencias(Request $request)
    {
        // Obtener todos los datos de la solicitud
        $data = $request->all();

        // Acceder a los datos del formulario con valores predeterminados si no existen
        $form_id = $data['form']['id'] ?? 'N/A';
        $form_name = $data['form']['name'] ?? 'N/A';

        // Acceder a los campos individuales con valores predeterminados si no existen
        $nombre = $data['fields']['nombre']['value'] ?? 'N/A';
        $telefono = $data['fields']['telefono']['value'] ?? 'N/A';
        $correo = $data['fields']['correo']['value'] ?? 'N/A';
        $mensaje = $data['fields']['mensaje']['value'] ?? 'N/A';
        $archivo = $data['fields']['archivo']['value'] ?? 'N/A';

        // Acceder a los datos meta con valores predeterminados si no existen
        $date = $data['meta']['date']['value'] ?? 'N/A';
        $time = $data['meta']['time']['value'] ?? 'N/A';
        $page_url = $data['meta']['page_url']['value'] ?? 'N/A';
        $user_agent = $data['meta']['user_agent']['value'] ?? 'N/A';
        $remote_ip = $data['meta']['remote_ip']['value'] ?? 'N/A';
        $credit = $data['meta']['credit']['value'] ?? 'N/A';

        $log_data = [
            'form_id' => $form_id,
            'form_name' => $form_name,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'correo' => $correo,
            'mensaje' => $mensaje,
            'archivo' => $archivo,
            'date' => $date,
            'time' => $time,
            'page_url' => $page_url,
            'user_agent' => $user_agent,
            'remote_ip' => $remote_ip,
            'credit' => $credit,
        ];



        return response()->json(['message' => 'success']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BlogSugerencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class BlogSugerenciasWebhookController extends Controller
{
    public function handleBlogSugerencias(Request $request)
    {
        try {
            // Log::info('Request data:', $request->all());
            // exit();

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


            // Descargar el archivo desde la URL
            $fileUrl = $archivo;
            $client = new Client();
            $response = $client->get($fileUrl);

            // Verificar que la descarga fue exitosa
            if ($response->getStatusCode() == 200) {
                $fileContent = $response->getBody()->getContents();
                $fileName = basename($fileUrl);
                $filePath = 'public/blog/' . $fileName;

                // Guardar el archivo en el almacenamiento de Laravel
                Storage::put($filePath, $fileContent);

                $dataToLog = [
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'telefono' => $telefono,
                    'mensaje' => $mensaje,
                    'archivo' => $fileName,
                    'page_url' => $page_url,
                    'user_agent' => $user_agent,
                    'remote_ip' => $remote_ip,
                    'powered_by' => $credit
                ];


                BlogSugerencia::create($dataToLog);
                return response()->json(['message' => 'Datos recibidos y almacenados correctamente'], 200);
            }

            return response()->json(['message' => 'No se ha podido descargar el archivo.'], 400);
        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;


class BibliotecaWebhookController extends Controller
{
    public function handleBiblioteca(Request $request)
    {
        try {
            // Log::info('Request data:', $request->all());

            $data = $request->only([
                'Nombre_Completo',
                'Telefono',
                'Correo',
                'Mensaje',
                'No_Label_archivo', // URL del archivo
                'Date',
                'Time',
                'Page_URL',
                'User_Agent',
                'Remote_IP',
                'Powered_by',
                'form_id',
                'form_name'
            ]);

            // Descargar el archivo desde la URL
            $fileUrl = $data['No_Label_archivo'];
            $client = new Client();
            $response = $client->get($fileUrl);

            // Verificar que la descarga fue exitosa
            if ($response->getStatusCode() == 200) {
                $fileContent = $response->getBody()->getContents();
                $fileName = basename($fileUrl);
                $filePath = 'public/biblioteca/' . $fileName;

                // Guardar el archivo en el almacenamiento de Laravel
                Storage::put($filePath, $fileContent);

                $dataToLog = [
                    'nombre' => $data['Nombre_Completo'],
                    'apellido' => '',
                    'correo' => $data['Correo'],
                    'telefono' => $data['Telefono'],
                    'descripcion' => $data['Mensaje'],
                    'archivo' => $fileName,
                ];

                Biblioteca::create($dataToLog);
                return response()->json(['message' => 'Datos recibidos y almacenados correctamente'], 200);
            }

            return response()->json(['message' => 'No se ha podido descargar el archivo.'], 400);
        } catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
}

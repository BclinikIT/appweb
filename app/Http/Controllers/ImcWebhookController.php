<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Imc_Formulario;
use App\Models\Imc_Invitacion;

class ImcWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        try {
            $nombre = $request->input('Nombre:');
            $apellido = $request->input('Apellido:');
            $edad = $request->input('Edad:');
            $genero = $request->input('Genero:');
            $peso_en_libras = $request->input('Peso_en_libras:');
            $altura_en_centimetros = $request->input('Altura_en_cms:');
            $correo = $request->input('Correo:');
            $telefono = $request->input('Telefono:');


            $formName = $request->input('form_name');
            $form_id=$request->input('form_id');
            $dataToLog = [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'edad' => $edad,
                'genero' => $genero,

                'peso_en_libras' => $peso_en_libras,
                'altura_en_cms' => $altura_en_centimetros,
                'correo' => $correo,
                'telefono'=>$telefono,
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'form_id'=>"0"
            ];

            Imc_Formulario::create($dataToLog);






          //  Log::info('Datos específicos recibidos del webhook:', $data);


            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y registrarla
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
    public function handleImcInvitacion(Request $request)
    {
        try {
            $data =$request->all();

            $nombre = $request->input('Tu_Nombre');
            $nombre_invitado = $request->input('Nombre_Referido');
            $telefono_referido = $request->input('Telefono_Referido');
            $email_referido = $request->input('Email_Referido');
            $form_id = $request->input('form_id');

            $dataToSave = [
                'nombre' => $nombre,
                'apellido' => '',
                'nombre_invitado' => $nombre_invitado,
                'apellido_invitado' => '',
                'correo' => $email_referido,
                'telefono' => $telefono_referido,
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'form_id' => $form_id
            ];
            Imc_Invitacion::create($dataToSave);


            Log::info('Datos específicos recibidos del webhook:', $data);

        }
        catch (\Exception $e) {
            Log::error('Error al procesar datos del webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos del webhook'], 500);
        }
    }
}

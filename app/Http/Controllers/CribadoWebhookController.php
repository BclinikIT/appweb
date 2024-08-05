<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cribado_Form_Cotizacion;
use PHPMailer\PHPMailer\PHPMailer;
use PDF;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Services\EmailService;

class CribadoWebhookController extends Controller
{

    protected $emailService;
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    public function pdf(Request $request){
        $encryptedId = $request->query('id');
        $decryptedId = Crypt::decryptString($encryptedId);
        $datos = Cribado_Form_Cotizacion::findOrFail($decryptedId);


        $data = [
            'date' => date('d-m-Y'),
            'nombre_de_quien_solicita' => $datos->nombre_de_quien_solicita,
            'nombre_de_la_empresa' => $datos->nombre_de_la_empresa,
            'puesto_en_la_empresa' => $datos->puesto_en_la_empresa,
        ];

        $pdf = PDF::loadView('pdf.cribado_cotizacion', $data);
        return $pdf->download('Respuesta_Cotización_Cribado.pdf');
    }


    public function handleCribadoCotizacion(Request $request)
    {
        try {
            $data = $request->only([
                'Nombre_de_la_empresa',
                'Dirección',
                'Cantidad_de_colaboradores_en_total',
                'Nombre_de_quien_solicita',
                'Puesto_en_la_empresa',
                'Teléfono_directo_–_móvil',
                'Email',
                'Date',
                'Time',
                'Page_URL',
                'User_Agent',
                'Remote_IP',
                'Powered_by',
                'form_id',
                'form_name'
            ]);

            $dataToInsert = [
                'nombre_de_la_empresa' => $data['Nombre_de_la_empresa'] ?? '',
                'direccion' => $data['Dirección'] ?? '',
                'cantidad_de_colaboradores_en_total' => $data['Cantidad_de_colaboradores_en_total'] ?? '',
                'nombre_de_quien_solicita' => $data['Nombre_de_quien_solicita'] ?? '',
                'puesto_en_la_empresa' => $data['Puesto_en_la_empresa'] ?? '',
                'telefono_directo_movil' => $data['Teléfono_directo_–_móvil'] ?? '',
                'email' => $data['Email'] ?? '',
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'page_url' => $data['Page_URL'] ?? 'null',
                'user_agent' => $data['User_Agent'] ?? 'null',
                'remote_ip' => $data['Remote_IP'] ?? 'null',
                'powered_by' => $data['Powered_by'] ?? 'null',
                'form_id' => $data['form_id'] ?? 'null',
                'form_name' => $data['form_name'] ?? 'null',
            ];

            // Save the data and create a new Cribado_Form_Cotizacion record
            $newCribado = Cribado_Form_Cotizacion::create($dataToInsert);
            $encryptedId = Crypt::encryptString($newCribado->id);
            $link = url('pdf/download/cribado_cotizacion') . '?id=' . urlencode($encryptedId);

            // Prepare email data for the Blade view
            $emailData = [
                'date' => date('d-m-Y'),
                'nombre_de_quien_solicita' => $data['Nombre_de_quien_solicita'] ?? '',
                'nombre_de_la_empresa' => $data['Nombre_de_la_empresa'] ?? '',
                'puesto_en_la_empresa' => $data['Puesto_en_la_empresa'] ?? '',
                'link' => $link,
            ];

            // Generate PDF content
      /*       $pdf = PDF::loadView('pdf.cribado_cotizacion', $emailData);
            $pdfContent = $pdf->output(); */

            // Prepare and send the email
            $recipient = $data['Email'];
            $subject = 'Respuesta Cotización Cribado';
            $view = 'emails.cribado_cotizacion';
            $attachments = [
             //   ['content' => $pdfContent, 'name' => 'Respuesta_Cotización_Cribado.pdf']
            ];

            $this->emailService->sendEmail(
                $recipient,
                $subject,
                $view,
                $emailData,
                $attachments,
                'noreply@bclinik.com',
                '+Bio Clinik',
                'noreply@bclinik.com',
                '+Bio Clinik'
            );

           // return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            Log::error('Error al procesar la solicitud: ' . $e->getMessage());
            return response()->json(['message' => 'Error al procesar la solicitud'], 500);
        }
    }

}

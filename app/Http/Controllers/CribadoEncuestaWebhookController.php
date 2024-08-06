<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\EncuestaCribado;
use App\Services\EmailService;
use PHPMailer\PHPMailer\PHPMailer;
use PDF;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Crypt;

class CribadoEncuestaWebhookController extends Controller
{
    protected $emailService;
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function pdf(Request $request){
        $encryptedId = $request->query('id');
        $decryptedId = Crypt::decryptString($encryptedId);
        $datos = EncuestaCribado::findOrFail($decryptedId);

      $dataToPDF = [
            'date' => date('d-m-Y'),
            'nombre' => $datos->nombre,
            'apellido' => $datos->apellido,
            'result' => $datos->result,
            'imc' => $datos->imc,
            'sumatoria' => $datos->habitos,
            'categoria' => $datos->categoria,
            'antecedentes_familiares' => $datos->antecedentes_porcentaje,
            'factores_riesgo' => $datos->factores_porcentaje,
            'factores_hereditarios' => $datos->hereditarios_porcentaje,
            'promedio' => $datos->porcentaje_promedio_final,
        ];





        $pdf = PDF::loadView('pdf.cribado_encuesta', $dataToPDF);
        return $pdf->download('Resultado de la Encuesta de Cribado.pdf');


    }
    public function handleCribadoEncuesta(Request $request)
    {
        try {
            // Extraer los datos de los campos del formulario
            $data = $request->input('fields', []);

            // Asignar valores de los campos a variables
            $nombre = $data['name']['value'] ?? '';
            $apellido = $data['apellido']['value'] ?? '';
            $edad = $data['edad']['value'] ?? '';
            $telefono_personal = $data['telefono_personal']['value'] ?? '';
            $correo = $data['email']['value'] ?? '';
            $empresa = $data['empresa']['value'] ?? '';
            $sede = $data['sede']['value'] ?? '';
            $genero = $data['genero']['value'] ?? '';
            $peso_en_libras = $data['peso_en_libras']['value'] ?? '';
            $altura_en_cms = $data['altura_en_cms']['value'] ?? '';
            $cintura_en_cms = $data['cintura_en_cms']['value'] ?? '';

            $agua_diaria = $data['agua_diaria']['value'] ?? '';
            $horarios_refacciones_comidas = $data['horarios_refacciones_comidas']['value'] ?? '';
            $porciones_pequenas = $data['porciones_pequenas']['value'] ?? '';
            $separar_combinar_alimentos = $data['separar_combinar_alimentos']['value'] ?? '';
            $rutina_ejercicio = $data['rutina_ejercicio']['value'] ?? '';
            $vivir_saludable = $data['vivir_saludable']['value'] ?? '';

            $insulina_alta = $data['insulina_alta']['value'] ?? '';
            $resistencia_insulina = $data['resistencia_insulina']['value'] ?? '';
            $elevaciones_azucar_embarazo = $data['elevaciones_azucar_embarazo']['value'] ?? '';
            $sindrome_ovarios_poliquistico = $data['sindrome_ovarios_poliquistico']['value'] ?? '';

            $sobrepeso = $data['sobrepeso']['value'] ?? '';
            $diabetes_embarazo_hijo = $data['diabetes_embarazo_hijo']['value'] ?? '';
            $ejercicio_regular = $data['ejercicio_regular']['value'] ?? '';
            $ovario_poliquistico = $data['ovario_poliquistico']['value'] ?? '';

            $padre = $data['padre']['value'] ?? '';
            $madre = $data['madre']['value'] ?? '';
            $hermanos = $data['hermanos']['value'] ?? '';
            $tios_paternos = $data['tios_paternos']['value'] ?? '';
            $tios_maternos = $data['tios_maternos']['value'] ?? '';
            $abuelos_maternos = $data['abuelos_maternos']['value'] ?? '';
            $abuelos_paternos = $data['abuelos_paternos']['value'] ?? '';

            // Calcular IMC y categoría
            $peso_r = ($peso_en_libras / 2.20462);
            $talla = ($altura_en_cms / 100);
            $tallas_r = ($talla * $talla);
            $result = round(($peso_r / $tallas_r) * 10 / 10, 2);

            $categoria = $this->obtenerCategoriaIMC($result);

            // Calcular sumatoria
            $sumatoria = $this->asignarValor($agua_diaria) + $this->asignarValor($horarios_refacciones_comidas) +
                         $this->asignarValor($porciones_pequenas) + $this->asignarValor($separar_combinar_alimentos) +
                         $this->asignarValor($rutina_ejercicio) + $this->asignarValor($vivir_saludable);

            // Determinar nivel de riesgo
            $habitos_costumbre_riesgos = $this->determinarNivelRiesgo($result);

            // Calcular porcentajes de antecedentes y factores de riesgo
            $antecedentesTotal = $this->asignarValorPorcentaje($insulina_alta) +
                                 $this->asignarValorPorcentaje($resistencia_insulina) +
                                 $this->asignarValorPorcentaje($elevaciones_azucar_embarazo) +
                                 $this->asignarValorPorcentaje($sindrome_ovarios_poliquistico);

            $factoresTotales = $this->asignarValorPorcentaje($sobrepeso) +
                               $this->asignarValorPorcentaje($diabetes_embarazo_hijo) +
                               $this->asignarValorPorcentaje($ejercicio_regular) +
                               $this->asignarValorPorcentaje($ovario_poliquistico);

            $hereditariorsTotal = $this->calcularHereditariosTotal(
                $padre, $madre, $hermanos, $tios_paternos, $tios_maternos, $abuelos_maternos, $abuelos_paternos
            );

            $antecedentesPorcentaje = min($antecedentesTotal, 95);
            $factoresPorcentaje = min($factoresTotales, 95);
            $hereditariosPorcentaje = min($hereditariorsTotal, 95);

            $promedio = ($antecedentesTotal + $factoresTotales + $hereditariosPorcentaje) / 3;
            $porcentajePromedioFinal = min(round($promedio, 2), 95);

            $imc = $result;
            $habitos = $sumatoria;
            $antecedentes_porcentaje = $antecedentesPorcentaje;
            $factores_porcentaje = $factoresPorcentaje;
            $hereditarios_porcentaje = $hereditariosPorcentaje;
            $porcentaje_promedio_final = $porcentajePromedioFinal;




            // Insertar datos en la base de datos
            $dataToInsert = compact(
            'nombre', 'apellido', 'edad', 'telefono_personal', 'correo', 'empresa', 'sede', 'genero',
            'peso_en_libras', 'altura_en_cms', 'cintura_en_cms', 'imc', 'categoria', 'habitos',
            'agua_diaria', 'horarios_refacciones_comidas', 'porciones_pequenas', 'separar_combinar_alimentos',
            'rutina_ejercicio', 'vivir_saludable', 'insulina_alta', 'resistencia_insulina',
            'elevaciones_azucar_embarazo', 'sindrome_ovarios_poliquistico', 'sobrepeso',
            'diabetes_embarazo_hijo', 'ejercicio_regular', 'ovario_poliquistico', 'padre',
            'madre', 'hermanos', 'tios_paternos', 'tios_maternos', 'abuelos_maternos',
            'abuelos_paternos', 'antecedentes_porcentaje', 'factores_porcentaje',
            'hereditarios_porcentaje', 'porcentaje_promedio_final'
        );

            $newEncuesta = EncuestaCribado::create($dataToInsert);

            $encryptedId = Crypt::encryptString($newEncuesta->id);
            $link = url('/pdf/download/cribado_encuesta') . '?id=' . urlencode($encryptedId);

            // Preparar datos para la vista PDF
            $dataToPDF = compact(
                'nombre', 'apellido', 'result', 'categoria', 'sumatoria',
                'antecedentesPorcentaje', 'factoresPorcentaje', 'hereditariosPorcentaje', 'porcentajePromedioFinal'
            );

           /*  $pdf = PDF::loadView('pdf.cribado_encuesta', $dataToPDF);
            $pdfContent = $pdf->output(); */

            // Enviar correo

            $DataToEmail = [
                'date'=> date('d-m-Y'),
                'link' => $link,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'imc' => $result,
                'categoria' => $categoria,
                'sumatoria' => $sumatoria,
                'antecedentesPorcentaje'=> $antecedentesPorcentaje,
                'factoresPorcentaje' => $factoresPorcentaje,
                'hereditariosPorcentaje' => $hereditariosPorcentaje,
                'porcentajePromedioFinal' => $porcentajePromedioFinal,


            ];
            $recipient = $correo;

            $subject = 'Resultado de la Encuesta de Cribado';
            $view = 'emails.cribado_encuesta';
            $emailData = $DataToEmail;
            $attachments = [
                // ['content' => $pdfContent, 'name' => 'Respuesta_Calculadora_IMC.pdf']
            ];

            $fromName = '+Bio Clinik';
            $replyToName = '+Bio Clinik';
            $this->emailService->sendEmail($recipient, $subject, $view, $emailData, $attachments, 'noreply@bclinik.com', $fromName, 'noreply@bclinik.com', $replyToName);

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al procesar la encuesta'], 500);
        }
    }

    private function obtenerCategoriaIMC($imc)
    {
        if ($imc < 18.5) {
            return "Infrapeso";
        } elseif ($imc >= 18.5 && $imc < 25) {
            return "Normal";
        } elseif ($imc >= 25 && $imc < 30) {
            return "Sobrepeso";
        } elseif ($imc >= 30 && $imc < 40) {
            return "Obesidad";
        } elseif ($imc >= 40) {
            return "Obesidad mórbida";
        } else {
            return "Valor IMC no válido";
        }
    }

    private function asignarValor($respuesta)
    {
        switch(trim($respuesta)) {
            case 'Si':
                return 2;
            case 'A veces':
                return 1;
            case 'No':
                return 0;
            default:
                return 0;
        }
    }

    private function determinarNivelRiesgo($result)
    {
        if ($result < 25) {
            return '<p style="color: #76b82a;font-size: 2em;font-weight: bold;">Bajo</p>
                    <p>&nbsp;¡Tienes un Estilo de Vida Saludable!</p>
                    <p>&nbsp;Tienes el metabolismo acelerado<br type="_moz"></p><i></i>';
        } elseif ($result >= 25 && $result <= 29) {
            return '<p style="color: #d7d436;font-size: 2em;font-weight: bold;">Medio<br type="_moz"></p>
                    <p>¡Tu Estilo de Vida puede mejorar!<br type="_moz"></p>
                    <p>Tienes el metabolismo irregular<br type="_moz"></p>';
        } elseif ($result >= 30 && $result <= 39) {
            return '<p style="color: red;font-size: 2em;font-weight: bold;">Alto&nbsp; &nbsp; &nbsp;<br type="_moz"></p>
                    <p>¡Debes cambiar tu Estilo de Vida!<br type="_moz"></p>
                    <p>Tienes el metabolismo lento<br type="_moz"></p>';
        } else {
            return '<p style="color: red;font-size: 2em;font-weight: bold;">Muy Alto&nbsp; &nbsp; &nbsp;<br type="_moz"></p>
                    <p>¡Debes cambiar tu Estilo de Vida!<br type="_moz"></p>
                    <p>Tienes el metabolismo lento<br type="_moz"></p>';
        }
    }

    private function asignarValorPorcentaje($respuesta)
    {
        $respuesta = strtolower($respuesta);
        switch(trim($respuesta)) {
            case 'si':
                return 25;
            case 'no':
                return 0;
            default:
                return 0;
        }
    }

    private function calcularHereditariosTotal($padre, $madre, $hermanos, $tios_paternos, $tios_maternos, $abuelos_maternos, $abuelos_paternos)
    {
        $total = 20;
        if (strtolower($padre) == 'si' || strtolower($madre) == 'si') {
            $total += 35;
        }
        if (strtolower($hermanos) == 'si') {
            $total += 30;
        }
        if (strtolower($tios_paternos) == 'si' || strtolower($tios_maternos) == 'si') {
            $total += 25;
        }
        if (strtolower($abuelos_maternos) == 'si' || strtolower($abuelos_paternos) == 'si') {
            $total += 20;
        }
        return $total;
    }
}

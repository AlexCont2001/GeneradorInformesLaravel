<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use BcMath\Number;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class WordController extends Controller
{
    public function generarInforme(Estudiante $estudiante)
    {
        try {
            $templatePath = storage_path('app/public/templates/TemplatePrimerCiclo.docx');
            $template = new TemplateProcessor($templatePath);
            $data = Estudiante::with(['calificaciones', 'calificaciones.ponderacion.asignatura', 'curso'])->find($estudiante->id);

            $template->setValue('e.nombre', ucwords(strtolower($data->nombres . ' ' . $data->apellido_paterno . ' ' . $data->apellido_materno)));
            $template->setValue('e.rut', $data->rut);
            $template->setValue('c.nombre', ucwords(strtolower($data->curso->nombre)));
            $template->setValue('c.profesor', ucwords(strtolower($data->curso->profesor)));
            $template->setValue('fecha', Carbon::now()->format('d-m-Y'));

            $map = [
                1 => 'm.',
                2 => 'l.',
                3 => 'h.',
                4 => 'c.',
                5 => 'i.',
                6 => 'te.',
                7 => 'f.',
                8 => 'ta.',
                9 => 'o.',
                10 => 'r.',
                11 => 'mu.',
                12 => 'a.'
            ];
            $fields = [
                'n1' => 'n1',
                'n2' => 'n2',
                'n3' => 'n3',
                'n4' => 'n4',
                'n5' => 'n5',
                'n6' => 'n6',
                'avg' => 'promedio'
            ];
            $conceptuals = [
                'MB',
                'B',
                'S',
                'I'
            ];

            foreach ($data->calificaciones as $calificacion) {
                $asignaura_id = $calificacion->ponderacion->asignatura->id;
                if (!isset($map[$asignaura_id])) {
                    continue;
                }
                $prefijo = $map[$asignaura_id];
                foreach ($fields as $field_key => $field_db) {
                    $valorDB = $calificacion->$field_db;
                    $fieldFormatted = ($valorDB == 0) ? '----' : $valorDB;
                    $fieldFormatted = (intval($valorDB)) ? number_format($valorDB, 1, '.', '') : $fieldFormatted;
                    if ($calificacion->ponderacion->asignatura->conceptual) {
                        if ($valorDB >= 5.5 && $valorDB <= 7.0) {
                            $fieldFormatted = 'MB';
                        } elseif ($valorDB >= 4.5 && $valorDB <= 5.4) {
                            $fieldFormatted = 'B';
                        } elseif ($valorDB >= 3.5 && $valorDB <= 4.4) {
                            $fieldFormatted = 'S';
                        } elseif ($valorDB > 0 && $valorDB <= 3.4) {
                            $fieldFormatted = 'I';
                        } else {
                            $fieldFormatted = '----';
                        }
                    }
                    $template->setValue($prefijo . $field_key, $fieldFormatted);
                }
            }
            $promedio_general = (intval($data->promedio)) ? number_format($data->promedio, 1, '.', '') : $data->promedio;
            $template->setValue('e.avg', $promedio_general);
            $filename = 'Informe_' . $data->curso->nombre . '_' . $estudiante->nombres .  $estudiante->apellido_paterno . $estudiante->apellido_materno . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'word_');
            $template->saveAs($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MatriculaPdfController extends Controller
{
    public function __invoke(Request $request, Matricula $matricula)
    {
        // Carga todo lo que el blade necesita (docente y módulo de la sección)
        $matricula->load([
            'estudiante.apoderado',
            'seccion.docente',
            'seccion.modulo',
        ]);

        $pdf = Pdf::loadView('pdfs.matricula', compact('matricula'))
            ->setPaper('a4', 'portrait');

        $filename = 'Matricula-' . ($matricula->codigo ?? $matricula->id) . '.pdf';

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MatriculaPdfController extends Controller
{
    public function __invoke(Request $request, Matricula $matricula)
    {
        $matricula->load(['estudiante.apoderado', 'seccion']);

        $pdf = Pdf::loadView('pdfs.matricula', compact('matricula'))
            ->setPaper('a4', 'portrait');

        $filename = 'Matricula-' . ($matricula->codigo ?? $matricula->id) . '.pdf';

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        // Vista previa en el navegador
        return $pdf->stream($filename);
    }
}

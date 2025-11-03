<?php

use Illuminate\Support\Facades\Route;// routes/web.php
// routes/web.php
use App\Http\Controllers\MatriculaPdfController;


Route::middleware(['web', 'auth']) // ajusta a tu proyecto
    ->get('/matriculas/{matricula}/pdf', MatriculaPdfController::class)
    ->name('matriculas.pdf');


Route::get(' ',function () { #Configurado para que la ruta por defecto sea la principal
    return redirect('/admin');
});
<?php

use Illuminate\Support\Facades\Route;

Route::get(' ',function () { #Configurado para que la ruta por defecto sea la principal
    return view('welcome');
});

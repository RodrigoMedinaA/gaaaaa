<?php

namespace App\Enums;

enum Modalidad: string
{
    case PRESENCIAL = 'Presencial';
    case VIRTUAL = 'Virtual';
    case SEMIPRESENCIAL = 'Semipresencial';
}

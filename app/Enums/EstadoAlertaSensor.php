<?php

namespace App\Enums;

enum EstadoAlertaSensor: string
{
    case NORMAL = 'normal';
    case ALTO   = 'alto';
    case BAJO   = 'bajo';
}
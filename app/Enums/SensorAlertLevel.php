<?php

namespace App\Enums;

enum SensorAlertLevel: string
{
    case NORMAL = 'normal';
    case HIGH   = 'high';
    case LOW   = 'low';
}
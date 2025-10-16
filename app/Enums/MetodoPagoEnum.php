<?php

namespace App\Enums;

enum MetodoPagoEnum: string
{
    case Efectivo = 'EFECTIVO';
    case Tarjeta = 'QR';
}

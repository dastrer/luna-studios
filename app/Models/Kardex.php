<?php

namespace App\Models;

use App\Enums\TipoTransaccionEnum;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Kardex extends Model
{
    protected $guarded = ['id'];

    protected $table = 'kardex';

    protected $casts = ['tipo_transaccion' => TipoTransaccionEnum::class];

    private const MARGEN_GANANCIA = 0.2;

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class); // ✅ CORREGIDO
    }

    public function getFechaAttribute(): string
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getHoraAttribute(): string
    {
        return $this->created_at->format('h:i A');
    }

    public function getCostoTotalAttribute(): float
    {
        return $this->saldo * $this->costo_unitario;
    }

    /**
     * Crear un registro en el Kardex
     */
    public function crearRegistro(array $data, TipoTransaccionEnum $tipo): void
    {
        $entrada = null;
        $salida = null;
        $saldo = null;

        $ultimoRegistro = $this->where('producto_id', $data['producto_id'])
            ->latest('id')
            ->first();

        // Lógica corregida para APERTURA
        if ($tipo == TipoTransaccionEnum::Apertura) {
            $entrada = $data['cantidad'];
            $saldo = $entrada; // En apertura, el saldo inicial es la cantidad
        } elseif ($tipo == TipoTransaccionEnum::Compra) {
            $entrada = $data['cantidad'];
            $saldo = $ultimoRegistro ? $ultimoRegistro->saldo + $entrada : $entrada;
        } elseif ($tipo == TipoTransaccionEnum::Venta || $tipo == TipoTransaccionEnum::Ajuste) {
            $salida = $data['cantidad'];
            $saldo = $ultimoRegistro ? $ultimoRegistro->saldo - $salida : -$salida;
        }

        try {
            $this->create([
                'producto_id' => $data['producto_id'],
                'tipo_transaccion' => $tipo,
                'descripcion_transaccion' => $this->getDescripcionTransaccion($data, $tipo),
                'entrada' => $entrada,
                'salida' => $salida,
                'saldo' => $saldo,
                'costo_unitario' => $data['costo_unitario'] ?? 0, // ✅ Maneja null para equipos
            ]);
        } catch (Exception $e) {
            Log::error('Error al crear un registro en el kardex', [
                'error' => $e->getMessage(),
                'data' => $data,
                'tipo' => $tipo->value
            ]);
            throw $e; // ✅ Relanza la excepción para que el controlador la capture
        }
    }

    /**
     * Obtener la descripción según el tipo de Transacción
     */
    private function getDescripcionTransaccion(array $data, TipoTransaccionEnum $tipo): string
    {
        return match ($tipo) {
            TipoTransaccionEnum::Apertura => 'Apertura del producto',
            TipoTransaccionEnum::Compra => 'Entrada de producto por la compra n°' . ($data['compra_id'] ?? 'N/A'),
            TipoTransaccionEnum::Venta => 'Salida de producto por la venta n°' . ($data['venta_id'] ?? 'N/A'),
            TipoTransaccionEnum::Ajuste => 'Ajuste de producto',
            default => 'Transacción no especificada'
        };
    }

    /**
     * Obtener el precio de Venta según el costo del Producto
     */
    public function calcularPrecioVenta(int $producto_id): float
    {
        $ultimoRegistro = $this->where('producto_id', $producto_id)
            ->latest('id')
            ->first();

        if (!$ultimoRegistro || !$ultimoRegistro->costo_unitario) {
            return 0; // O algún valor por defecto
        }

        return $ultimoRegistro->costo_unitario + round($ultimoRegistro->costo_unitario * self::MARGEN_GANANCIA, 2);
    }
}
<?php

namespace App\Listeners;

use App\Events\CreateCompraDetalleEvent;
use App\Models\Inventario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInventarioCompraListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(CreateCompraDetalleEvent $event): void
    {
        // Buscamos el registro de inventario existente para el producto.
        $registro = Inventario::where('producto_id', $event->producto_id)->first();

        // Si existe un registro, lo actualizamos.
        if ($registro) {
            $registro->update([
                // CAMBIO: Solo se actualiza la cantidad sumando el stock comprado.
                // Se elimina la referencia a 'fecha_vencimiento'.
                'cantidad' => ($registro->cantidad + $event->cantidad),
            ]);
        } else {
            // Manejar la creación del registro si no existe. 
            // Aunque este Listener parece solo enfocado en la actualización, es buena práctica manejar el caso.
            // Para mantener la consistencia, asumiremos que si no existe, el inventario se crea en otro proceso 
            // o aquí (dependiendo de la lógica de tu aplicación). 
            // Si el inventario debe crearse aquí:
            Inventario::create([
                'producto_id' => $event->producto_id,
                'cantidad' => $event->cantidad,
                // Nota: Los campos de ubicación ('ubicacione_id') que puedan ser NOT NULL 
                // deben ser manejados aquí con valores por defecto o lógica específica, 
                // pero por ahora solo manejamos lo relacionado con el Evento.
            ]);
        }
    }
}

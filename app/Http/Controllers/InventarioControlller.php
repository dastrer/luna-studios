<?php

namespace App\Http\Controllers;

use App\Enums\TipoTransaccionEnum;
use App\Http\Requests\StoreInventarioRequest;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Ubicacione;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InventarioControlller extends Controller
{
    function __construct()
    {
        $this->middleware('check_producto_inicializado', ['only' => ['create', 'store']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $inventario = Inventario::latest()->get();
        return view('inventario.index', compact('inventario'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $producto = Producto::findOrfail($request->producto_id);
        $ubicaciones = Ubicacione::all();
        
        // Determinar si es servicio (S) o equipo (E)
        $tipoProducto = 'equipo'; // por defecto
        if ($producto->codigo && strtoupper(substr($producto->codigo, 0, 1)) === 'S') {
            $tipoProducto = 'servicio';
        }
        
        return view('inventario.create', compact('producto', 'ubicaciones', 'tipoProducto'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreInventarioRequest $request, Kardex $kardex): RedirectResponse
{
    DB::beginTransaction();
    try {
        $data = $request->validated();
        
        // Determinar tipo de producto basado en el código
        $producto = Producto::find($data['producto_id']);
        $tipoProducto = 'equipo';
        if ($producto->codigo && strtoupper(substr($producto->codigo, 0, 1)) === 'S') {
            $tipoProducto = 'servicio';
        }
        
        // Aplicar reglas específicas según el tipo de producto
        if ($tipoProducto === 'servicio') {
            // SERVICIOS: Forzar ubicación en Estante 1 y cantidad infinita
            $estante1 = Ubicacione::where('nombre', 'Estante 1')->first();
            if (!$estante1) {
                throw new \Exception('No se encontró el Estante 1 en el sistema');
            }
            $data['ubicacione_id'] = $estante1->id;
            $data['cantidad'] = 999999;
            $data['fecha_vencimiento'] = null;
            
            // Validar que el costo esté presente para servicios
            if (!isset($data['costo_unitario']) || $data['costo_unitario'] === null || $data['costo_unitario'] <= 0) {
                throw new \Exception('El costo unitario es requerido y debe ser mayor a 0 para servicios');
            }
        } else {
            // EQUIPOS: Validar que no esté en Estante 1
            $ubicacion = Ubicacione::find($data['ubicacione_id']);
            if ($ubicacion && $ubicacion->nombre === 'Estante 1') {
                throw new \Exception('Los equipos no pueden ubicarse en el Estante 1');
            }
            
            // Para equipos, el costo unitario y fecha de vencimiento son null
            $data['costo_unitario'] = null;
            $data['fecha_vencimiento'] = null;
            
            // Validar que la cantidad sea mayor a 0
            if (!isset($data['cantidad']) || $data['cantidad'] <= 0) {
                throw new \Exception('La cantidad debe ser mayor a 0 para equipos');
            }
        }

        // PREPARAR DATOS PARA INVENTARIO (solo campos que existen en la tabla)
        $inventarioData = [
            'producto_id' => $data['producto_id'],
            'ubicacione_id' => $data['ubicacione_id'],
            'cantidad' => $data['cantidad'],
            'cantidad_minima' => null, // Si tu tabla tiene estos campos
            'cantidad_maxima' => null, // Si tu tabla tiene estos campos
        ];

        // Crear el registro en inventario (solo con campos válidos)
        $inventario = Inventario::create($inventarioData);
        
        // Crear registro en kardex (con todos los datos incluyendo costo_unitario)
        $kardex->crearRegistro($data, TipoTransaccionEnum::Apertura);
        
        DB::commit();
        
        ActivityLogService::log('Inicialización de producto', 'Productos', $data);
        return redirect()->route('productos.index')->with('success', 'Producto inicializado exitosamente');
        
    } catch (Throwable $e) {
        DB::rollBack();
        Log::error('Error al inicializar el producto', [
            'error' => $e->getMessage(), 
            'data' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
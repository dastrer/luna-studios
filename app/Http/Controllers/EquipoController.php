<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacione;
use App\Models\Producto;
use App\Services\ActivityLogService;
use App\Services\ProductoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // AÑADIR ESTA IMPORTACIÓN
use Illuminate\Support\Facades\Log;
use Throwable;

class EquipoController extends Controller
{
    protected $productoService;

    function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
        $this->middleware('permission:ver-producto|crear-producto|editar-producto|eliminar-producto', ['only' => ['index']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $query = Producto::with([
                'categoria.caracteristica',
                'marca.caracteristica', 
                'presentacione.caracteristica'
            ])
            ->where('codigo', 'LIKE', 'E%'); // FILTRO: Solo equipos que empiezan con E

        // Aplicar filtro por marca si existe
        if (request()->has('marca_id') && request('marca_id') != '') {
            if (request('marca_id') == 'sin_marca') {
                $query->whereNull('marca_id');
            } else {
                $query->where('marca_id', request('marca_id'));
            }
        }

        $equipos = $query->latest()->get();

        return view('equipo.index', compact('equipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('equipo.create', compact('marcas', 'presentaciones', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipoRequest $request): RedirectResponse
    {
        try {
            $this->productoService->crearProducto($request->validated());
            ActivityLogService::log('Creación de equipo', 'Equipos', $request->validated());
            
            return redirect()->route('equipos.index')->with('success', 'Equipo registrado correctamente');
        } catch (Throwable $e) {
            Log::error('Error al crear el equipo', ['error' => $e->getMessage()]);
            
            return redirect()->route('equipos.index')->with('error', 'Ups, algo falló');
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
    public function edit(Producto $equipo, Request $request): View // AÑADIR Request $request
    {
        // Verificar que el equipo tenga código que empiece con E (opcional, para seguridad)
        if ($equipo->codigo && !str_starts_with($equipo->codigo, 'E')) {
            abort(404, 'Equipo no encontrado');
        }

        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        // Pasar el parámetro redirect_to a la vista
        $redirectTo = $request->get('redirect_to', 'equipos');

        return view('equipo.edit', compact('equipo', 'marcas', 'presentaciones', 'categorias', 'redirectTo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipoRequest $request, Producto $equipo): RedirectResponse
    {
        try {
            $this->productoService->editarProducto($request->validated(), $equipo);
            ActivityLogService::log('Edición de equipo', 'Equipos', $request->validated());
            
            // Obtener el destino de redirección del formulario
            $redirectTo = $request->get('redirect_to', 'equipos.index');
            
            // Redirigir según el parámetro
            if ($redirectTo === 'equipos' || $redirectTo === 'equipos.index') {
                return redirect()->route('equipos.index')->with('success', 'Equipo editado correctamente');
            } else {
                // Por defecto redirigir a equipos
                return redirect()->route('equipos.index')->with('success', 'Equipo editado correctamente');
            }
            
        } catch (Throwable $e) {
            Log::error('Error al editar el equipo', ['error' => $e->getMessage()]);
            
            // En caso de error, redirigir de vuelta con los datos
            return redirect()->back()->withInput()->with('error', 'Error al editar el equipo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
@extends('layouts.app')

@section('title','Servicios')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .filter-card {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Servicios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Servicios</li>
    </ol>

    @can('crear-producto')
    <div class="mb-4">
        <a href="{{route('productos.create')}}">
            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
        </a>
    </div>
    @endcan

    <!-- Card de Filtros -->
    <div class="card mb-4 filter-card">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form action="{{ route('productos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_id" name="categoria_id">
                        <option value="">Todas las categorías</option>
                        @php
                            // Obtener todas las categorías disponibles para el filtro
                            $categoriasFiltro = \App\Models\Categoria::with('caracteristica')
                                ->whereHas('caracteristica', function($query) {
                                    $query->where('estado', 1);
                                })
                                ->get();
                        @endphp
                        @foreach($categoriasFiltro as $categoria)
                            <option value="{{ $categoria->id }}" 
                                    {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->caracteristica->nombre }}
                            </option>
                        @endforeach
                        <option value="sin_categoria" {{ request('categoria_id') == 'sin_categoria' ? 'selected' : '' }}>
                            Sin categoría
                        </option>
                    </select>
                </div>
                <!-- Espacio reservado para futuros filtros -->
                <div class="col-md-4">
                    <!-- Aquí puedes agregar más filtros en el futuro -->
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
                <div class="col-12 d-flex align-items-end justify-content-end">
                    <small class="text-muted">
                        @php
                            // Filtrar productos cuyo código empieza con "S" y aplicar filtro de categoría
                            $serviciosFiltrados = $productos->filter(function($producto) {
                                $cumpleCodigo = $producto->codigo && strtoupper(substr($producto->codigo, 0, 1)) === 'S';
                                $cumpleCategoria = true;
                                
                                // Aplicar filtro de categoría si está activo
                                if (request()->has('categoria_id') && request('categoria_id') != '') {
                                    if (request('categoria_id') == 'sin_categoria') {
                                        $cumpleCategoria = $producto->categoria_id === null;
                                    } else {
                                        $cumpleCategoria = $producto->categoria_id == request('categoria_id');
                                    }
                                }
                                
                                return $cumpleCodigo && $cumpleCategoria;
                            });
                        @endphp
                        @if($serviciosFiltrados->count() > 0)
                            <span class="badge bg-primary">{{ $serviciosFiltrados->count() }} servicios encontrados</span>
                        @else
                            No se encontraron servicios
                        @endif
                    </small>
                </div>
            </form>
            
            @if(request()->has('categoria_id') && request('categoria_id') != '')
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Filtros aplicados:</strong>
                    @if(request('categoria_id') == 'sin_categoria')
                        Categoría: Sin categoría
                    @else
                        @php
                            $categoriaSeleccionada = $categoriasFiltro->firstWhere('id', request('categoria_id'));
                        @endphp
                        Categoría: {{ $categoriaSeleccionada->caracteristica->nombre ?? 'N/A' }}
                    @endif
                    | Código: Empieza con "S"
                </small>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla servicios
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Filtrar productos cuyo código empieza con "S" y aplicar filtro de categoría
                        $serviciosFiltrados = $productos->filter(function($producto) {
                            $cumpleCodigo = $producto->codigo && strtoupper(substr($producto->codigo, 0, 1)) === 'S';
                            $cumpleCategoria = true;
                            
                            // Aplicar filtro de categoría si está activo
                            if (request()->has('categoria_id') && request('categoria_id') != '') {
                                if (request('categoria_id') == 'sin_categoria') {
                                    $cumpleCategoria = $producto->categoria_id === null;
                                } else {
                                    $cumpleCategoria = $producto->categoria_id == request('categoria_id');
                                }
                            }
                            
                            return $cumpleCodigo && $cumpleCategoria;
                        });
                    @endphp

                    @foreach ($serviciosFiltrados as $item)
                    <tr>
                        <td>
                            {{ $item->nombre }}
                        </td>
                        <td>
                            {{ $item->categoria->caracteristica->nombre ?? 'Sin categoría' }}
                        </td>
                        <td>
                            <span class="badge rounded-pill text-bg-{{ $item->estado ? 'success' : 'danger' }}">
                                {{ $item->estado ? 'Activo' : 'Inactivo'}}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-around">
                                <div>
                                    <button title="Opciones"
                                        class="btn btn-datatable btn-icon btn-transparent-dark me-2"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <svg class="svg-inline--fa fa-ellipsis-vertical"
                                            aria-hidden="true" focusable="false"
                                            data-prefix="fas" data-icon="ellipsis-vertical"
                                            role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512"
                                            data-fa-i2svg="">
                                            <path fill="currentColor" d="M56 472a56 56 0 1 1 0-112 56 56 0 1 1 0 112zm0-160a56 56 0 1 1 0-112 56 56 0 1 1 0 112zM0 96a56 56 0 1 1 112 0A56 56 0 1 1 0 96z"></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu text-bg-light" style="font-size: small;">
                                        <!-----Editar Producto--->
                                        @can('editar-producto')
                                        <li><a class="dropdown-item" href="{{route('productos.edit',['producto' => $item])}}">
                                                Editar</a>
                                        </li>
                                        @endcan
                                        <!----Ver-producto--->
                                        @can('ver-producto')
                                        <li>
                                            <a class="dropdown-item" role="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#verModal-{{$item->id}}">
                                                Ver</a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                                <div>
                                    <!----Separador----->
                                    <div class="vr"></div>
                                </div>
                                <div>
                                    <!------Inicializar producto---->
                                    @can('crear-inventario')
                                    <form action="{{route('inventario.create')}}" method="get">
                                        <input type="hidden" name="producto_id" value="{{$item->id}}">
                                        <button title="Inicializar"
                                            class="btn btn-datatable btn-icon btn-transparent-dark"
                                            type="submit">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="verModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del servicio: {{ $item->nombre }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Código: </span>{{ $item->codigo ?? 'Sin código' }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Nombre: </span>{{ $item->nombre }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Precio: </span>{{ $item->precio ?? 'No aperturado' }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Categoría: </span>{{ $item->categoria->caracteristica->nombre ?? 'Sin categoría' }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Descripción: </span>{{$item->descripcion ?? 'No tiene'}}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="fw-bolder">Imagen:</p>
                                            <div>
                                                @if (!empty($item->img_path))
                                                <img src="{{ asset($item->img_path) }}" alt="{{ $item->nombre }}"
                                                    class="img-fluid img-thumbnail border border-4 rounded">
                                                @else
                                                <p>Sin imagen</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach

                    @if($serviciosFiltrados->count() === 0)
                    <tr>
                        <td colspan="4" class="text-center">
                            No se encontraron servicios con código que empiece con "S" 
                            @if(request()->has('categoria_id') && request('categoria_id') != '')
                                en la categoría seleccionada
                            @endif
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script>
    // Inicializar DataTables
    document.addEventListener('DOMContentLoaded', function() {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
            searchable: true,
            fixedHeight: false,
            perPage: 10
        });
    });
</script>
@endpush>
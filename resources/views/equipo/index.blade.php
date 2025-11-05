@extends('layouts.app')

@section('title','Equipos')

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
    <h1 class="mt-4 text-center">Equipos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Equipos</li>
    </ol>

    @can('crear-producto')
    <div class="mb-4">
        <a href="{{ route('equipos.create') }}">
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
            <form action="{{ route('equipos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="marca_id" class="form-label">Marca</label>
                    <select class="form-select" id="marca_id" name="marca_id">
                        <option value="">Todas las marcas</option>
                        @php
                            $marcasUnicas = $equipos->pluck('marca')->unique()->filter();
                        @endphp
                        @foreach($marcasUnicas as $marca)
                            @if($marca)
                            <option value="{{ $marca->id }}" 
                                    {{ request('marca_id') == $marca->id ? 'selected' : '' }}>
                                {{ $marca->caracteristica->nombre ?? 'Sin nombre' }}
                            </option>
                            @endif
                        @endforeach
                        <option value="sin_marca" {{ request('marca_id') == 'sin_marca' ? 'selected' : '' }}>
                            Sin marca
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
                        <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
                <div class="col-12 d-flex align-items-end justify-content-end">
                    <small class="text-muted">
                        @if($equipos->count() > 0)
                            <span class="badge bg-primary">{{ $equipos->count() }} equipos encontrados</span>
                        @else
                            No se encontraron equipos
                        @endif
                    </small>
                </div>
            </form>
            
            @if(request()->has('marca_id'))
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Filtros aplicados:</strong>
                    @if(request('marca_id') == 'sin_marca')
                        Marca: Sin marca
                    @elseif(request('marca_id'))
                        @php
                            $marcaSeleccionada = $marcasUnicas->firstWhere('id', request('marca_id'));
                        @endphp
                        Marca: {{ $marcaSeleccionada->caracteristica->nombre ?? 'N/A' }}
                    @endif
                </small>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla equipos
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Precio Adquirido (Bs)</th>
                        <th>Marca</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipos as $item)
                    <tr>
                        <td>
                            {{ $item->nombre }}
                        </td>
                        <td>
                            Bs. {{ number_format($item->precio, 2) ?? 'No aperturado' }}
                        </td>
                        <td>
                            {{ $item->marca->caracteristica->nombre ?? 'Sin marca' }}
                        </td>
                        <td>
                            <span class="badge rounded-pill text-bg-{{ $item->estado ? 'success' : 'danger' }}">
                                {{ $item->estado ? 'Activo' : 'Inactivo' }}
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
                                        <!-----Editar Equipo--->
                                        @can('editar-producto')
                                        <li><a class="dropdown-item" href="{{ route('equipos.edit',['equipo' => $item]) }}">
                                                Editar</a>
                                        </li>
                                        @endcan
                                        <!----Ver Equipo--->
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
                                    <!------Inicializar equipo---->
                                    @can('crear-inventario')
                                    <form action="{{ route('inventario.create') }}" method="get">
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
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del equipo: {{ $item->codigo }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Nombre: </span>{{ $item->nombre }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Precio Adquirido: </span>Bs. {{ number_format($item->precio, 2) }}</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p><span class="fw-bolder">Descripción: </span>{{ $item->descripcion ?? 'No tiene' }}</p>
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
@endpush
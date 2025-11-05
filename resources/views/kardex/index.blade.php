@extends('layouts.app')

@section('title','C. Equipos')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@push('css')
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Control de Equipos e Insumos</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item active='true' content="Control de Equipos" />
    </x-breadcrumb.template>

    <div class="mb-3">
        <form action="{{route('kardex.index')}}" method="get">
            <div class="row gy-2">
                <label for="producto_id" class="col-sm-2 col-form-label">
                    Recursos</label>
                <div class="col-sm-8">
                    <select name="producto_id" id="producto_id"
                        class="form-control selectpicker"
                        data-live-search='true' data-size='3' title='Busque un equipo o insumo aquí'>
                        @foreach ($productos as $item)
                            @if (strtoupper(substr($item->codigo, 0, 1)) === 'E')
                            <option value="{{$item->id}}" {{$item->id == $producto_id ? 'selected': ''}}>
                                {{$item->codigo}} - {{$item->nombre}}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary">
                        Buscar</button>
                </div>
            </div>
        </form>
    </div>

    @if ($kardex->count())
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla kardex del equipos e insumos
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table-striped fs-6">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Transacción</th>
                        <th>Descripción </th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Saldo</th>
                        <th>Costo unitario</th>
                        <th>Costo total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kardex as $item)
                    <tr>
                        <td>
                            {{$item->fecha}} - {{$item->hora}}
                        </td>
                        <td>
                            @if($item->tipo_transaccion == 'APERTURA')
                                UBICAR
                            @else
                                {{$item->tipo_transaccion}}
                            @endif
                        </td>
                        <td>
                            @php
                                $descripcion = $item->descripcion_transaccion;
                                // Reemplazar "producto" por "recurso" (case insensitive)
                                $descripcion = preg_replace('/producto/i', 'recurso', $descripcion);
                                // Reemplazar "Apertura del" por "Ubicación del"
                                $descripcion = preg_replace('/Apertura del/i', 'Ubicación del', $descripcion);
                            @endphp
                            {{$descripcion}}
                        </td>
                        <td>
                            {{$item->entrada}}
                        </td>
                        <td>
                            {{$item->salida}}
                        </td>
                        <td>
                            {{$item->saldo}}
                        </td>
                        <td>
                            {{$item->costo_unitario}}
                        </td>
                        <td>
                            {{$item->costo_total}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    @else
    <p class="text-center my-5">Sin datos</p>
    @endif


</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function() {
        // Configurar el select para mostrar productos que empiezan con "E" al abrir
        $('#producto_id').on('shown.bs.select', function () {
            var $searchbox = $(this).next().find('.bs-searchbox input');
            $searchbox.val('E');
            $searchbox.trigger('input');
        });

        // Mostrar todos los registros en la tabla
        window.addEventListener('DOMContentLoaded', event => {
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple, {
                    perPage: 100, // Mostrar muchos registros por página
                    perPageSelect: false, // Ocultar selector de registros por página
                });
            }
        });
    });
</script>
@endpush
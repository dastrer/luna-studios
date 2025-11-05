@extends('layouts.app')

@section('title','Ver entrega')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ver Entrega</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Entregas</a></li>
        <li class="breadcrumb-item active">Ver Entrega</li>
    </ol>
</div>

<div class="container-fluid">

    <div class="card mb-4">

        <div class="card-header">
            Datos generales de la entrega
        </div>

        <div class="card-body">
            <h6 class="card-subtitle mb-3 text-body-secondary">
                Comprobante: {{$venta->comprobante->nombre}} ({{$venta->numero_comprobante}})</h6>
            <h6 class="card-subtitle mb-3 text-body-secondary">
                Cliente: {{$venta->cliente->persona->razon_social}}</h6>
            <h6 class="card-subtitle mb-3 text-body-secondary">
                Atendido por: {{$venta->user->name}}</h6>
            <h6 class="card-subtitle mb-3 text-body-secondary">
                Método de pago: {{$venta->metodo_pago}}</h6>
            <h6 class="card-subtitle mb-3 text-body-secondary">
                Fecha y hora: {{$venta->fecha}} - {{$venta->hora}}</h6>
            <hr>
        </div>
    </div>


    <!---Tabla--->
    <div class="card mb-2">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla de detalle de la entrega
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr class="align-top">
                        <th class="text-white">Servicio/Producto Entregado</th>
                        <th class="text-white">Cantidad</th>
                        <th class="text-white">Precio de entrega</th>
                        <th class="text-white">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->productos as $item)
                    <tr>
                        <td>
                            {{$item->nombre}}
                        </td>
                        <td>
                            {{$item->pivot->cantidad}}
                        </td>
                        <td>
                            {{$item->pivot->precio_venta}}
                        </td>
                        <td class="td-subtotal">
                            {{($item->pivot->cantidad) * ($item->pivot->precio_venta)}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4"></th>
                    </tr>
                    <tr>
                        <th colspan="3">Total:</th>
                        <th>
                            {{$venta->total}} {{$empresa->moneda->simbolo}}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection

@push('js')
@endpush
@extends('layouts.app')

@section('title','Inicializar Producto')

@push('css')
<style>
    .form-control:read-only {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Gestionar Adquisiciones y Servicios</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('productos.index')" content="Productos" />
        <x-breadcrumb.item active='true' content="G. Adquisiciones y Servicios" />
    </x-breadcrumb.template>

    <div class="mb-4">
        <button type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#verPlanoModal">
            Ver plano
        </button>
    </div>

    <x-forms.template :action="route('inventario.store')" method='post'>

        <x-slot name='header'>
            <p>Nombre: <span class='fw-bold'>{{$producto->nombre}}</span></p>
            <p class="text-muted">
                @if($tipoProducto === 'servicio')
                    <i class="fas fa-info-circle"></i> Servicio - Configure el costo del servicio
                @else
                    <i class="fas fa-info-circle"></i> Equipo - Seleccione ubicación y cantidad
                @endif
            </p>
        </x-slot>

        <div class="row g-4">

            <!-----Producto id---->
            <input type="hidden" name="producto_id" value="{{$producto->id}}">
            <input type="hidden" name="tipo_producto" value="{{$tipoProducto}}">

            @if($tipoProducto === 'servicio')
            <!-- SERVICIOS (S): Solo mostrar costo unitario -->
            
            <!---Costo Unitario (solo para servicios)----->
            <div class="col-md-6">
                <label for="costo_unitario" class="form-label">Costo del servicio:</label>
                <input type="number" 
                       step="0.01" 
                       name="costo_unitario" 
                       id="costo_unitario" 
                       class="form-control" 
                       value="{{ old('costo_unitario') }}" 
                       required
                       placeholder="0.00"
                       min="0">
                <small class="text-muted">Ingrese el costo del servicio</small>
                @error('costo_unitario')
                <small class="text-danger">{{'*'.$message}}</small>
                @enderror
            </div>

            <!-- Campos ocultos para servicios con valores fijos -->
            <input type="hidden" name="ubicacione_id" value="{{ $ubicaciones->firstWhere('nombre', 'Estante 1')->id ?? '' }}">
            <input type="hidden" name="cantidad" value="999999">
            <input type="hidden" name="fecha_vencimiento" value="">

            @else
            <!-- EQUIPOS (E): Mostrar ubicación y cantidad, ocultar costo y fecha -->

            <!---Ubicaciones (solo para equipos)-->
            <div class="col-6">
                <label for="ubicacione_id" class="form-label">Ubicación:</label>
                <select name="ubicacione_id"
                    id="ubicaciones_id"
                    class="form-select"
                    required>
                    <option value="">Seleccione una ubicación</option>
                    @foreach ($ubicaciones as $item)
                        @if($item->nombre !== 'Estante 1')
                        <option value="{{$item->id}}" {{ old('ubicacione_id') == $item->id ? 'selected' : '' }}>
                            {{$item->nombre}}
                        </option>
                        @endif
                    @endforeach
                </select>
                @error('ubicacione_id')
                <small class="text-danger">{{'*'.$message}}</small>
                @enderror
            </div>

            <!---Cantidad (solo para equipos)--->
            <div class="col-md-6">
                <label for="cantidad" class="form-label">Cantidad:</label>
                <input type="number" 
                       name="cantidad" 
                       id="cantidad" 
                       class="form-control" 
                       value="{{ old('cantidad', 1) }}" 
                       min="1"
                       required>
                <small class="text-muted">Ingrese la cantidad de equipos</small>
                @error('cantidad')
                <small class="text-danger">{{'*'.$message}}</small>
                @enderror
            </div>

            <!-- Campos ocultos para equipos -->
            <input type="hidden" name="costo_unitario" value="">
            <input type="hidden" name="fecha_vencimiento" value="">

            @endif

        </div>

        <x-slot name='footer'>
            <button type="submit" class="btn btn-primary">Inicializar</button>
        </x-slot>

    </x-forms.template>

    <!-- Modal -->
    <div class="modal fade" id="verPlanoModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Plano de Ubicaciones</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ asset('assets/img/plano.png')}}" alt="Plano de ubicaciones"
                                class="img-fluid img-thumbail border rounded">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const tipoProducto = '{{$tipoProducto}}';
        
        // Validación para equipos (no permitir Estante 1)
        if (form && tipoProducto === 'equipo') {
            form.addEventListener('submit', function(e) {
                const ubicacionSelect = document.getElementById('ubicaciones_id');
                if (ubicacionSelect) {
                    const selectedOption = ubicacionSelect.options[ubicacionSelect.selectedIndex];
                    if (selectedOption.textContent.includes('Estante 1')) {
                        e.preventDefault();
                        alert('Los equipos no pueden ubicarse en el Estante 1. Por favor seleccione otra ubicación.');
                        ubicacionSelect.focus();
                    }
                }
            });
        }

        // Para servicios, validar que el costo sea mayor a 0
        if (tipoProducto === 'servicio') {
            form.addEventListener('submit', function(e) {
                const costoInput = document.getElementById('costo_unitario');
                if (costoInput && (!costoInput.value || parseFloat(costoInput.value) <= 0)) {
                    e.preventDefault();
                    alert('El costo del servicio debe ser mayor a 0.');
                    costoInput.focus();
                }
            });
        }
    });
</script>
@endpush
@extends('layouts.app')

@section('title','Crear Servicio')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Servicio</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('productos.index')}}">Servicios</a></li>
        <li class="breadcrumb-item active">Crear servicio</li>
    </ol>

    <div class="card">
        <form action="{{ route('productos.store') }}" method="post" enctype="multipart/form-data" id="servicioForm">
            @csrf
            <div class="card-body text-bg-light">

                <div class="row g-4">

                    <!---Nombre---->
                    <div class="col-12">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!---Descripción---->
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion')}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                </div>

                <br>

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="row g-4">

                            <!---Imagen---->
                            <div class="col-12">
                                <label for="img_path" class="form-label">Imagen:</label>
                                <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                                @error('img_path')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!----Codigo---->
                            <div class="col-12">
                                <label for="codigo_input" class="form-label">Abreviacion:</label>
                                <div class="input-group">
                                    <span class="input-group-text">S</span>
                                    <input type="text" 
                                           name="codigo_input" 
                                           id="codigo_input" 
                                           class="form-control" 
                                           value="{{ old('codigo_input') }}"
                                           placeholder="001"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    <input type="hidden" name="codigo" id="codigo_real">
                                </div>
                                <small class="text-muted">Formato: S + números (ej: S001, S123)</small>
                                @error('codigo')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            {{-- Marca oculta --}}
                            <input type="hidden" name="marca_id" value="">

                            <!---Presentaciones---->
                            <div class="col-12">
                                <label for="presentacione_id" class="form-label">Paquete:</label>
                                <select data-size="4"
                                    title="Seleccione una presentación"
                                    data-live-search="true"
                                    name="presentacione_id"
                                    id="presentacione_id"
                                    class="form-control selectpicker show-tick">
                                    @foreach ($presentaciones as $item)
                                    <option value="{{$item->id}}" {{ old('presentacione_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('presentacione_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!---Categorías---->
                            <div class="col-12">
                                <label for="categoria_id" class="form-label">Categoría:</label>
                                <select data-size="4"
                                    title="Seleccione la categoría"
                                    data-live-search="true"
                                    name="categoria_id"
                                    id="categoria_id"
                                    class="form-control selectpicker show-tick">
                                    <option value="">No tiene categoría</option>
                                    @foreach ($categorias as $item)
                                    <option value="{{$item->id}}" {{ old('categoria_id') == $item->id ? 'selected' : '' }}>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <p>Imagen del servicio:</p>

                        <img id="img-default"
                            class="img-fluid"
                            src="{{ asset('assets/img/paisaje.png') }}"
                            alt="Imagen por defecto">

                        <img src="" alt="Ha cargado un archivo no compatible"
                            id="img-preview"
                            class="img-fluid img-thumbnail" style="display: none;">

                    </div>

                </div>
            </div>

            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    const inputImagen = document.getElementById('img_path');
    const imagenPreview = document.getElementById('img-preview');
    const imagenDefault = document.getElementById('img-default');

    inputImagen.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagenPreview.src = e.target.result;
                imagenPreview.style.display = 'block';
                imagenDefault.style.display = 'none';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Prefijo automático para el código de servicios
    document.addEventListener('DOMContentLoaded', function() {
        const codigoInput = document.getElementById('codigo_input');
        const codigoReal = document.getElementById('codigo_real');
        const form = document.getElementById('servicioForm');

        // Validar que solo se ingresen números
        codigoInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            // Actualizar el campo real en tiempo real
            if (this.value) {
                codigoReal.value = 'S' + this.value;
            } else {
                codigoReal.value = '';
            }
        });

        // Asegurar que se envíe el código con "S" antes del submit
        form.addEventListener('submit', function(e) {
            const codigoValue = codigoInput.value;
            if (codigoValue) {
                codigoReal.value = 'S' + codigoValue;
            }
        });

        // También actualizar cuando la página carga (para edit)
        if (codigoInput.value) {
            codigoReal.value = 'S' + codigoInput.value;
        }
    });
</script>
@endpush
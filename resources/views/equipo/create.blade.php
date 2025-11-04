@extends('layouts.app')

@section('title','Crear Equipo')

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
    <h1 class="mt-4 text-center">Crear Equipo</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('equipos.index')}}">Equipos</a></li>
        <li class="breadcrumb-item active">Crear equipo</li>
    </ol>

    <div class="card">
        <form action="{{ route('equipos.store') }}" method="post" enctype="multipart/form-data" id="equipoForm">
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

                            <!---Precio---->
                            <div class="col-12">
                                <label for="precio" class="form-label">Precio Adquirido:</label>
                                <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="{{old('precio')}}" required>
                                @error('precio')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

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
                                    <span class="input-group-text">E</span>
                                    <input type="text" 
                                           name="codigo_input" 
                                           id="codigo_input" 
                                           class="form-control" 
                                           value="{{ old('codigo_input') }}"
                                           placeholder="001"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                           required>
                                    <input type="hidden" name="codigo" id="codigo_real">
                                </div>
                                <small class="text-muted">Formato: E + números (ej: E001, E123)</small>
                                @error('codigo')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!---Marca---->
                            <div class="col-12">
                                <label for="marca_id" class="form-label">Marca:</label>
                                <select data-size="4"
                                    title="Seleccione una marca"
                                    data-live-search="true"
                                    name="marca_id"
                                    id="marca_id"
                                    class="form-control selectpicker show-tick">
                                    <option value="">No tiene marca</option>
                                    @foreach ($marcas as $item)
                                    <option value="{{$item->id}}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                                    @endforeach
                                </select>
                                @error('marca_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

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

                            {{-- Categoría oculta --}}
                            <input type="hidden" name="categoria_id" value="">

                        </div>

                    </div>
                    <div class="col-md-6">
                        <p>Imagen del equipo:</p>

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

    // Prefijo automático para el código
    document.addEventListener('DOMContentLoaded', function() {
        const codigoInput = document.getElementById('codigo_input');
        const codigoReal = document.getElementById('codigo_real');
        const form = document.getElementById('equipoForm');

        // Validar que solo se ingresen números
        codigoInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            // Actualizar el campo real en tiempo real
            if (this.value) {
                codigoReal.value = 'E' + this.value;
            } else {
                codigoReal.value = '';
            }
        });

        // Asegurar que se envíe el código con "E" antes del submit
        form.addEventListener('submit', function(e) {
            const codigoValue = codigoInput.value;
            if (codigoValue) {
                codigoReal.value = 'E' + codigoValue;
            }
            
            // Validar que tenga al menos un número
            if (!codigoValue) {
                e.preventDefault();
                alert('Por favor ingrese un código numérico');
                codigoInput.focus();
                return false;
            }
        });

        // También actualizar cuando la página carga (para edit)
        if (codigoInput.value) {
            codigoReal.value = 'E' + codigoInput.value;
        }
    });
</script>
@endpush
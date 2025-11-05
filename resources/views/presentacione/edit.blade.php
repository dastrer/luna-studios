@extends('layouts.app')

@section('title','Editar paquete')

@push('css')
<style>
    #descripcion {
        resize: none;
    }
    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Paquete</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('presentaciones.index')}}">Paquetes</a></li>
        <li class="breadcrumb-item active">Editar Paquete</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('presentaciones.update',['presentacione'=>$presentacione]) }}" method="post" id="paqueteForm">
            @method('PATCH')
            @csrf
            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre',$presentacione->caracteristica->nombre)}}">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sigla_input" class="form-label">Sigla:</label>
                        <div class="input-group">
                            <span class="input-group-text">P</span>
                            <input type="text" 
                                   name="sigla_input" 
                                   id="sigla_input" 
                                   class="form-control" 
                                   value="{{ old('sigla_input', substr($presentacione->sigla, 1)) }}"
                                   placeholder="001"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   maxlength="3"
                                   required>
                            <input type="hidden" name="sigla" id="sigla_real" value="{{ old('sigla', $presentacione->sigla) }}">
                        </div>
                        <small class="text-muted">Formato: P + números (ej: P001, P123)</small>
                        @error('sigla')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion',$presentacione->caracteristica->descripcion)}}</textarea>
                        @error('descripcion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                </div>

            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="reset" class="btn btn-secondary">Reiniciar</button>
            </div>
        </form>
    </div>


</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const siglaInput = document.getElementById('sigla_input');
        const siglaReal = document.getElementById('sigla_real');
        const form = document.getElementById('paqueteForm');

        // Validar que solo se ingresen números
        siglaInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            // Actualizar el campo real en tiempo real
            if (this.value) {
                siglaReal.value = 'P' + this.value;
            } else {
                siglaReal.value = '';
            }
        });

        // Asegurar que se envíe la sigla con "P" antes del submit
        form.addEventListener('submit', function(e) {
            const siglaValue = siglaInput.value;
            if (siglaValue) {
                siglaReal.value = 'P' + siglaValue;
            }
            
            // Validar que tenga al menos un número
            if (!siglaValue) {
                e.preventDefault();
                alert('Por favor ingrese un código numérico para la sigla');
                siglaInput.focus();
                return false;
            }

            // Validar que no exceda 3 dígitos
            if (siglaValue.length > 3) {
                e.preventDefault();
                alert('La sigla no puede tener más de 3 dígitos');
                siglaInput.focus();
                return false;
            }
        });

        // Inicializar con el valor actual (quitando la "P" del código existente)
        if (siglaInput.value) {
            siglaReal.value = 'P' + siglaInput.value;
        }

        // Manejar el botón de reiniciar
        const resetButton = document.querySelector('button[type="reset"]');
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                // Restaurar el valor original después del reset
                setTimeout(function() {
                    const originalValue = "{{ substr($presentacione->sigla, 1) }}";
                    siglaInput.value = originalValue;
                    siglaReal.value = 'P' + originalValue;
                }, 0);
            });
        }
    });
</script>
@endpush
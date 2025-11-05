@extends('layouts.app')

@section('title','Crear empleado')

@push('css')
<style>
    .image-container {
        position: relative;
        min-height: 200px;
        border: 2px dashed #dee2e6;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        overflow: hidden;
        padding: 20px;
    }
    .image-container:hover {
        border-color: #0d6efd;
        background-color: #e9ecef;
    }
    .image-loading {
        border-color: #0d6efd;
        background-color: #e7f1ff;
    }
    .loading-spinner {
        display: none;
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
    }
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
    .image-placeholder {
        text-align: center;
        color: #6c757d;
    }
    .image-placeholder i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #adb5bd;
    }
    .image-preview {
        max-width: 100%;
        max-height: 250px;
        object-fit: contain;
        display: none;
    }
    .remove-image-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: all 0.3s ease;
    }
    .remove-image-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Empleado</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('empleados.index')" content="Empleados" />
        <x-breadcrumb.item active='true' content="Crear empleado" />
    </x-breadcrumb.template>

    <x-forms.template :action="route('empleados.store')" method='post' file='true'>

        <div class="row g-4">

            <div class="col-md-6">
                <x-forms.input id="razon_social" required='true' labelText='Razon Social' />
            </div>

            <div class="col-md-6">
                <x-forms.input id="cargo" required='true' labelText='Función' />
            </div>

            <div class="col-md-6">
                <x-forms.input id="img" type='file' labelText='Seleccione una imagen' accept="image/*"/>
            </div>

            <div class="col-md-6">
                <p class="fw-bold mb-2">Imagen del empleado:</p>
                
                <div class="image-container" id="imageContainer">
                    <div class="loading-spinner" id="loadingSpinner"></div>
                    
                    <!-- Botón para quitar imagen -->
                    <button type="button" class="remove-image-btn" id="removeImageBtn" title="Quitar imagen">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <!-- Solo usamos el placeholder, no la imagen por defecto -->
                    <div class="image-placeholder" id="imagePlaceholder">
                        <i class="fas fa-user-circle"></i>
                        <div class="mt-2">
                            <small class="text-muted d-block">Haga clic en "Seleccione una imagen"</small>
                            <small class="text-muted">Formatos: JPG, PNG, GIF</small>
                        </div>
                    </div>

                    <!-- Imagen de vista previa -->
                    <img src="" 
                         alt="Vista previa de la imagen seleccionada"
                         id="img-preview"
                         class="image-preview">
                </div>
            </div>

        </div>

        <x-slot name='footer'>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </x-slot>

    </x-forms.template>

</div>
@endsection

@push('js')
<script>
    const inputImagen = document.getElementById('img');
    const imagenPreview = document.getElementById('img-preview');
    const imageContainer = document.getElementById('imageContainer');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const removeImageBtn = document.getElementById('removeImageBtn');

    inputImagen.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            // Validar que sea una imagen
            if (!this.files[0].type.startsWith('image/')) {
                alert('Por favor, seleccione un archivo de imagen válido (JPG, PNG, GIF).');
                this.value = '';
                return;
            }

            // Validar tamaño máximo (5MB)
            if (this.files[0].size > 5 * 1024 * 1024) {
                alert('La imagen es demasiado grande. Máximo 5MB permitido.');
                this.value = '';
                return;
            }

            // Mostrar estado de carga
            imageContainer.classList.add('image-loading');
            loadingSpinner.style.display = 'block';
            imagePlaceholder.style.display = 'none';
            imagenPreview.style.display = 'none';
            removeImageBtn.style.display = 'none';

            const reader = new FileReader();

            reader.onload = function(e) {
                // Ocultar spinner y mostrar imagen después de un breve delay
                setTimeout(() => {
                    loadingSpinner.style.display = 'none';
                    imageContainer.classList.remove('image-loading');
                    imagenPreview.src = e.target.result;
                    imagenPreview.style.display = 'block';
                    removeImageBtn.style.display = 'flex';
                    
                    console.log('Imagen cargada correctamente');
                }, 300);
            }

            reader.onerror = function() {
                // Manejar error de lectura
                loadingSpinner.style.display = 'none';
                imageContainer.classList.remove('image-loading');
                resetImagePreview();
                alert('Error al cargar la imagen. Intente con otra imagen.');
                console.error('Error al leer el archivo');
            }

            reader.readAsDataURL(this.files[0]);
        } else {
            // Si no hay archivo, restaurar estado inicial
            resetImagePreview();
        }
    });

    // Función para quitar la imagen seleccionada
    removeImageBtn.addEventListener('click', function() {
        resetImagePreview();
        inputImagen.value = ''; // Limpiar el input file
    });

    // Función para resetear la vista previa
    function resetImagePreview() {
        imagenPreview.style.display = 'none';
        imagePlaceholder.style.display = 'block';
        loadingSpinner.style.display = 'none';
        removeImageBtn.style.display = 'none';
        imageContainer.classList.remove('image-loading');
        imagenPreview.src = '';
    }

    // Resetear cuando se borre el input
    inputImagen.addEventListener('click', function() {
        if (this.value === '') {
            resetImagePreview();
        }
    });

    // También resetear al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        resetImagePreview();
    });
</script>

<!-- Agregar Font Awesome para los íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
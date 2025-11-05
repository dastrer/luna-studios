<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductoService
{
    /**
     * Crear un Registro
     */
    public function crearProducto(array $data): Producto
    {
        $producto = Producto::create([
            'codigo' => $data['codigo'] ?? null,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'precio' => $data['precio'] ?? null,
            'img_path' => isset($data['img_path']) && $data['img_path']
                ? $this->handleUploadImage($data['img_path'])
                : null,
            'marca_id' => $data['marca_id'] ?? null,
            'categoria_id' => $data['categoria_id'],
            'presentacione_id' => $data['presentacione_id'],
        ]);

        return $producto;
    }

    /**
     * Editar un registro
     */
    public function editarProducto(array $data, Producto $producto): Producto
    {
        // Preparar los datos para actualizar
        $updateData = [
            'codigo' => $data['codigo'] ?? null,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'precio' => $data['precio'] ?? null,
            'marca_id' => $data['marca_id'] ?? null,
            'categoria_id' => $data['categoria_id'],
            'presentacione_id' => $data['presentacione_id'],
        ];

        // Manejar la imagen solo si se proporciona una nueva
        if (isset($data['img_path']) && $data['img_path']) {
            $updateData['img_path'] = $this->handleUploadImage($data['img_path'], $producto->img_path);
        }

        $producto->update($updateData);

        return $producto;
    }

    /**
     * Guarda una imagen en el Storage
     */
    private function handleUploadImage(UploadedFile $image, $existingImagePath = null): string
    {
        // Eliminar imagen anterior si existe
        if ($existingImagePath) {
            $this->deleteImage($existingImagePath);
        }

        // Generar nombre único y guardar la imagen
        $name = uniqid() . '.' . $image->getClientOriginalExtension();
        $path = 'storage/' . $image->storeAs('productos', $name, 'public');
        
        return $path;
    }

    /**
     * Eliminar imagen del storage
     */
    private function deleteImage($imagePath): void
    {
        if ($imagePath) {
            $relative_path = str_replace('storage/', '', $imagePath);
            if (Storage::disk('public')->exists($relative_path)) {
                Storage::disk('public')->delete($relative_path);
            }
        }
    }
}
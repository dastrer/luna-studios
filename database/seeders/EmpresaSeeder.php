<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::insert([
            'nombre' => 'LUNA STUDIOS',
            'propietario' => 'Grover Tambo',
            'ruc' => '1089674538',
            'porcentaje_impuesto' => '0',
            'abreviatura_impuesto' => 'IVA',
            'direccion' => 'Av. Coperativa n°7104',
            'moneda_id' => 1
        ]);
    }
}

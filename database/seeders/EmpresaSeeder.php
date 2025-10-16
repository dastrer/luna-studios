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
            'nombre' => 'CALZADOS AGUILAR',
            'propietario' => 'Lourdes Aguilar',
            'ruc' => '1089674538',
            'porcentaje_impuesto' => '0',
            'abreviatura_impuesto' => 'IVA',
            'direccion' => 'Av. Los Pinos n°1064',
            'moneda_id' => 1
        ]);
    }
}

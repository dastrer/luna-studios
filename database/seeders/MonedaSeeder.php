<?php

namespace Database\Seeders;

use App\Models\Moneda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Moneda::insert([
            [
                'estandar_iso' => 'BOB',
                'nombre_completo' => 'Bolivianos',
                'simbolo' => 'Bs'
            ],
            [
                'estandar_iso' => 'EUR',
                'nombre_completo' => 'Euro',
                'simbolo' => '€'
            ],
            [
                'estandar_iso' => 'MXN',
                'nombre_completo' => 'Peso mexicano',
                'simbolo' => '$'
            ],
            [
                'estandar_iso' => 'PEN',
                'nombre_completo' => 'Sol peruano',
                'simbolo' => 'S/'
            ],
            [
                'estandar_iso' => 'ARS',
                'nombre_completo' => 'Peso Argentino',
                'simbolo' => '$'
            ],
            [
                'estandar_iso' => 'CLP',
                'nombre_completo' => 'Peso Chileno',
                'simbolo' => '$'
            ],
        ]);
    }
}

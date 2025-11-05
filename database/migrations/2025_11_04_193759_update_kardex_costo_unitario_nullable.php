<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Para MySQL
        DB::statement('ALTER TABLE kardex MODIFY costo_unitario DECIMAL(8,2) NULL');
        
        // O si prefieres el método de Schema (puede no funcionar en todas las versiones)
        // Schema::table('kardex', function (Blueprint $table) {
        //     $table->decimal('costo_unitario', 8, 2)->nullable()->change();
        // });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE kardex MODIFY costo_unitario DECIMAL(8,2) NOT NULL');
        
        // O
        // Schema::table('kardex', function (Blueprint $table) {
        //     $table->decimal('costo_unitario', 8, 2)->nullable(false)->change();
        // });
    }
};
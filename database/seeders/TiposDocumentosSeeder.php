<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposDocumentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los nombres de los roles
        $roles = ['Cedula de ciudadania', 'Tarjeta de identidad', 'Cedula de extranjeria',];

        foreach ($roles as $rol) {
            DB::table('tipo_documentos')->insert([
                'nombre' => $rol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

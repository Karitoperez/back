<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los nombres de los roles
        $roles = ['Administrador', 'Estudiante', 'Docente'];

        foreach ($roles as $rol) {
            DB::table('roles')->insert([
                'nombre' => $rol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Tecnología',
            'Negocios',
            'Diseño',
            'Desarrollo Personal',
            'Arte y Creatividad',
            'Marketing Digital',
            'Desarrollo web',
            'Desarrollo Movil',
            'Ciencia de Datos',
            'Educación',
            'Salud y Bienestar',
            'Idiomas',
            'Programación',
            'Finanzas',
            'Cocina y Gastronomía',
            'Viajes y Aventura',
            'Moda y Estilo',
            'Música',
            'Cine y Televisión',
            'Deportes y Fitness',
            'Medio Ambiente',
            'Historia',
            'Literatura',
            'Fotografía',
            'Matemáticas',
            'Psicología',
            'Espiritualidad',
            'Robótica',
            'Derecho',
            'Innovación',
            'Automatización'
        ];

        foreach ($categorias as $categoria) {
            Categoria::create([
                'nombre' => $categoria
            ]);
        }
    }
}

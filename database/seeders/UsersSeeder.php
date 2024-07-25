<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => $faker->firstName,
                'apellido' => $faker->lastName,
                'numero_documento' => $faker->randomNumber(8),
                'usuario' => $faker->userName,
                'fecha_nacimiento' => $faker->date,
                'direccion' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('yolo1007*'), // Se puede cambiar 'password' por la contraseña deseada
                'imagen' => $faker->imageUrl(),
                'id_tipo_documento' => $faker->numberBetween(1, 3), // Suponiendo que tienes 5 tipos de documento
                'id_rol' => $faker->numberBetween(1, 3), // Suponiendo que tienes 3 roles
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Acción'],
            ['name' => 'Animación'],
            ['name' => 'Arte'],
            ['name' => 'Aventura'],
            ['name' => 'Bélico'],
            ['name' => 'Biográfico'],
            ['name' => 'Ciencia Ficción'],
            ['name' => 'Comedia'],
            ['name' => 'Crimen'],
            ['name' => 'Deportivo'],
            ['name' => 'Drama'],
            ['name' => 'Fantasía'],
            ['name' => 'Histórico'],
            ['name' => 'Independiente'],
            ['name' => 'Infantil'],
            ['name' => 'Melodrama'],
            ['name' => 'Musical'],
            ['name' => 'Policíaco'],
            ['name' => 'Religioso'],
            ['name' => 'Suspense'],
            ['name' => 'Terror'],
            ['name' => 'Western'],
        ];

        DB::table('category')->insert($categories);
    }
}

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
            ['name' => 'Suspense'],
            ['name' => 'Acción'],
            ['name' => 'Terror'],
            ['name' => 'Aventura'],
            ['name' => 'Drama'],
            ['name' => 'Crimen'],
            ['name' => 'Épico'],
            ['name' => 'Fantasía'],
            ['name' => 'Animación'],
            ['name' => 'Comedia dramática'],
            ['name' => 'Romántico'],
            ['name' => 'Ciencia ficción'],
            ['name' => 'Biografía'],
            ['name' => 'Comedia'],
            ['name' => 'Comedia musical'],
            ['name' => 'Western'],
            ['name' => 'Histórico'],
            ['name' => 'Guerra'],
            ['name' => 'Familia'],
            ['name' => 'Judicial'],
            ['name' => 'Bollywood'],
            ['name' => 'Musical'],
            ['name' => 'Espionaje'],
        ];

        DB::table('categories')->insert($categories);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $roles = [
            ['name' => 'actor'],
            ['name' => 'director'],
            ['name' => 'writer'],
        ];

        foreach ($roles as $role){
            Roles::create([
                'name' => $role['name'],
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}

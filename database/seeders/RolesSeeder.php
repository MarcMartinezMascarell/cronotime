<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            [
            ['name' => 'trabajador', 'guard_name' => 'web'],
            ['name' => 'administrador', 'guard_name' => 'web'],
            ['name' => 'superAdmin', 'guard_name' => 'web'],
            ]
        );
    }
}

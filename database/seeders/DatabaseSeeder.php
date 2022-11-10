<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UsersTableSeeder::class, RolesSeeder::class]);
        DB::table('empresas')->insert([
            'nombre' => 'Autónomo',
            'workers_limit' => -1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('model_has_roles')->insert([
            'role_id' => '3',
            'model_type' => 'App\Models\User',
            'model_id' => '1'
        ]);
    }
}

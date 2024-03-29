<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            [
                'name' => 'SuperAdministrador',
                'email' => 'superadministrador@comedor.com',
                'password' => bcrypt('comedor1234'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('model_has_roles')->insert([
            [
                'role_id' => '1',
                'model_type' => 'App\User',
                'model_id' => '1',
            ]
        ]);
        DB::table('users')->insert([
            [
                'name' => 'Auditor',
                'email' => 'auditor@comedor.com',
                'password' => bcrypt('comedor1234'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        DB::table('model_has_roles')->insert([
            [
                'role_id' => '5',
                'model_type' => 'App\User',
                'model_id' => '2',
            ]
        ]);
    }
}

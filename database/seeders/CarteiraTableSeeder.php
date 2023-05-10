<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarteiraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('carteira')->insert([
                'user_id' => $user->id,
                'saldo' => rand(500, 5000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

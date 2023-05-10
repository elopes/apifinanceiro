<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User_PerfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();
        $tipos = array_merge(array_fill(0, 15, 'comum'), array_fill(0, 5, 'lojista'));
        shuffle($tipos);

        foreach ($users as $i => $user) {
            $tipo = $tipos[$i];
            $cpf = $tipo === 'comum' ? str_pad($i + 1, 11, '0', STR_PAD_LEFT) : null;
            $cnpj = $tipo === 'lojista' ? str_pad($i + 1, 14, '0', STR_PAD_LEFT) : null;

            DB::table('user_perfil')->insert([
                'user_id' => $user->id,
                'nome' => $user->name,
                'tipo' => $tipo,
                'cpf' => $cpf,
                'cnpj' => $cnpj,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

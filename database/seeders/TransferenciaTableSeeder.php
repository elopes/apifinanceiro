<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferenciaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();
        $user_perfis = DB::table('user_perfil')->get();
        $perfis_by_user_id = [];
        foreach ($user_perfis as $perfil) {
            $perfis_by_user_id[$perfil->user_id] = $perfil;
        }

        for ($i = 0; $i < 30; $i++) {
            do {
                $origem = $users->random();
            } while ($perfis_by_user_id[$origem->id]->tipo !== 'comum');

            do {
                $destinatario = $users->random();
            } while ($origem->id === $destinatario->id);

            DB::table('transferencia')->insert([
                'origem_id' => $origem->id,
                'transferido_de' => $origem->name,
                'destinatario_id' => $destinatario->id,
                'recebido_por' => $destinatario->name,
                'quantia' => rand(50, 250),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Alice', 'Bob', 'Charlie', 'Dave', 'Eve', 'Frank', 'Grace', 'Heidi', 'Ivan', 'Judy', 'Mallory', 'Niaj', 'Olivia', 'Peggy', 'Rupert', 'Sybil', 'Ted', 'Victor', 'Wendy', 'Zoe'];
        $emails = ['alice@example.com', 'bob@example.com', 'charlie@example.com', 'dave@example.com', 'eve@example.com', 'frank@example.com', 'grace@example.com', 'heidi@example.com', 'ivan@example.com', 'judy@example.com', 'mallory@example.com', 'niaj@example.com', 'olivia@example.com', 'peggy@example.com', 'rupert@example.com', 'sybil@example.com', 'ted@example.com', 'victor@example.com', 'wendy@example.com', 'zoe@example.com'];

        for ($i = 0; $i < 20; $i++) {
            DB::table('users')->insert([
                'name' => $names[$i],
                'email' => $emails[$i],
                'email_verified_at' => now(),
                'password' => Hash::make('senha'),
                'created_at' => now(),
                'updated_at' => now(),
                // O campo remember_token será preenchido automaticamente pelo Laravel
            ]);
        }
    }
}

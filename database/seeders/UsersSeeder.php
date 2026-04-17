<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Super admin user
            [
                'email' => 'superadmin@example.com',
                'data' => [
                    'name' => 'Super Admin',
                    'last_name' => 'System',
                    'phone' => '0600000000',
                    'password' => bcrypt('Password@1'),
                    'role' => 'superadmin',
                ],
            ],

            // Admin user
            [
                'email' => 'admin@example.com',
                'data' => [
                    'name' => 'Admin',
                    'last_name' => 'Hotel',
                    'phone' => '0601010101',
                    'password' => bcrypt('Password@1'),
                    'role' => 'admin',
                ],
            ],

            // Client users (Réalistes)
            [
                'email' => 'karim.alaoui@exemple.com',
                'data' => [
                    'name' => 'Karim',
                    'last_name' => 'Alaoui',
                    'phone' => '0612345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'sarah.benjelloun@exemple.com',
                'data' => [
                    'name' => 'Sarah',
                    'last_name' => 'Benjelloun',
                    'phone' => '0622345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'youssef.fahimi@exemple.com',
                'data' => [
                    'name' => 'Youssef',
                    'last_name' => 'Fahimi',
                    'phone' => '0632345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'leila.chraibi@exemple.com',
                'data' => [
                    'name' => 'Leila',
                    'last_name' => 'Chraibi',
                    'phone' => '0642345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'omar.tazi@exemple.com',
                'data' => [
                    'name' => 'Omar',
                    'last_name' => 'Tazi',
                    'phone' => '0652345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'nada.elmrini@exemple.com',
                'data' => [
                    'name' => 'Nada',
                    'last_name' => 'Elmrini',
                    'phone' => '0662345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'mehdi.bennani@exemple.com',
                'data' => [
                    'name' => 'Mehdi',
                    'last_name' => 'Bennani',
                    'phone' => '0672345678',
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                array_merge($user['data'], [
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    'updated_at' => DB::raw('NOW()'),
                ])
            );
        }
    }
}
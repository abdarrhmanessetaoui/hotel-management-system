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
                    'profile_image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1566492031773-4f4e44671857?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1542909168-82c3e7fdca5c?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=256&q=80',
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
                    'profile_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&q=80',
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
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Seed super admin, admin(s), and a few clients.
        // Uses `updateOrInsert` by unique `email` so you can re-run `php artisan db:seed`
        // without hitting duplicate-email errors.

        $users = [
            // Super admin user
            [
                'email' => 'superadmin@example.com',
                'data' => [
                    'name' => 'Super Admin',
                    'last_name' => 'System',
                    'phone' => null,
                    'password' => bcrypt('Password@1'),
                    'role' => 'superadmin',
                ],
            ],

            // Hotel admin user (assignable to hotels via hotels.admin_id)
            [
                'email' => 'admin1@example.com',
                'data' => [
                    'name' => 'Hotel Admin',
                    'last_name' => 'One',
                    'phone' => null,
                    'password' => bcrypt('Password@1'),
                    'role' => 'admin',
                ],
            ],
            [
                'email' => 'admin2@example.com',
                'data' => [
                    'name' => 'Hotel Admin',
                    'last_name' => 'Two',
                    'phone' => null,
                    'password' => bcrypt('Password@1'),
                    'role' => 'admin',
                ],
            ],

            // Client users
            [
                'email' => 'client1@example.com',
                'data' => [
                    'name' => 'Client',
                    'last_name' => 'One',
                    'phone' => null,
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'client2@example.com',
                'data' => [
                    'name' => 'Client',
                    'last_name' => 'Two',
                    'phone' => null,
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
            [
                'email' => 'client3@example.com',
                'data' => [
                    'name' => 'Client',
                    'last_name' => 'Three',
                    'phone' => null,
                    'password' => bcrypt('Password@1'),
                    'role' => 'client',
                ],
            ],
        ];

        foreach ($users as $user) {
            // Update existing user by email, or insert if missing.
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                array_merge($user['data'], [
                    // Keep timestamps current (Laravel will not do this for raw upserts).
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                    'updated_at' => DB::raw('NOW()'),
                ])
            );
        }
    }
}
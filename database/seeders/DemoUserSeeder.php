<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Raka Adiputra',        'superadmin@pijar.demo', 'super_admin',     null],
            ['Aulia Prameswari',     'admin@pijar.demo',      'admin_pijar_suar', null],
            ['Nadia Mahendra',       'kerma@pijar.demo',      'admin_kerma',     null],
            ['Dr. Rina Kusuma',      'kaprodi@pijar.demo',    'kaprodi',         'Business Information System'],
            ['Dimas Wiratama, M.T.', 'dosen@pijar.demo',      'dosen',           'Manajemen'],
        ];

        foreach ($users as [$name, $email, $role, $prodi]) {
            DB::table('users')->updateOrInsert(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('Demo123!'),
                    'role' => $role,
                    'study_program' => $prodi,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
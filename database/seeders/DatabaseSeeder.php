<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('lecturers')->insert([
            [
                'name' => 'Dr. Rina Kusuma',
                'study_program' => 'Business Information System',
                'institution' => 'Universitas Nusantara',
                'contact' => 'rina@example.test',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dimas Wiratama, M.T.',
                'study_program' => 'Manajemen',
                'institution' => 'Universitas Nusantara',
                'contact' => 'dimas@example.test',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dr. Ladesh Kotno',
                'study_program' => 'Informatic',
                'institution' => 'Universitas Nusantara',
                'contact' => 'ladesh@example.test',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Ambil ID dosen secara dinamis
        $rinaId = DB::table('lecturers')
            ->where('contact', 'rina@example.test')
            ->value('id');

        $dimasId = DB::table('lecturers')
            ->where('contact', 'dimas@example.test')
            ->value('id');

        $ladeshId = DB::table('lecturers')
            ->where('contact', 'ladesh@example.test')
            ->value('id');

        $users = [
            ['Raka Adiputra', 'superadmin@pijar.demo', 'super_admin', null, null],
            ['Aulia Prameswari', 'admin@pijar.demo', 'admin_pijar_suar', null, null],
            ['Nadia Mahendra', 'kerma@pijar.demo', 'admin_kerma', null, null],
            ['Dr. Rina Kusuma', 'kaprodi@pijar.demo', 'kaprodi', 'Business Information System', $rinaId],
            ['Dimas Wiratama, M.T.', 'dosen@pijar.demo', 'dosen', 'Business Information System', $dimasId],
            ['Dr. Ladesh Kotno', 'kaprodi1@pijar.demo', 'kaprodi', 'Informatic', $ladeshId],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u[1]],
                [
                    'name' => $u[0],
                    'password' => Hash::make('Demo123!'),
                    'role' => $u[2],
                    'study_program' => $u[3],
                    'lecturer_id' => $u[4],
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        DB::table('practitioners')->insert([
            [
                'name' => 'Bima Santoso',
                'institution' => 'Nusantara Tech',
                'position' => 'Head of Data',
                'competencies' => 'Data Analytics, Business Intelligence',
                'qualification' => '12 tahun pengalaman industri',
                'expertise' => 'Teknologi Informasi',
                'contact' => 'bima@example.test',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('partners')->insert([
            [
                'name' => 'Nusantara Tech',
                'industry' => 'Teknologi',
                'institution' => 'Nusantara Tech',
                'contact_name' => 'Bima Santoso',
                'contact' => 'partnership@example.test',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $rinaUserId = DB::table('users')
            ->where('email', 'kaprodi@pijar.demo')
            ->value('id');

        DB::table('submissions')->insert([
            [
                'code' => 'COURSES-DEMO-001',
                'program_type' => 'SUAR',
                'course_name' => 'Business Intelligence',
                'course_code' => 'BIS301',
                'study_program' => 'Business Information System',
                'lecturer_id' => $rinaId,
                'credits' => 3,
                'topic' => 'Implementasi dashboard analitik',
                'practitioner_competency' => 'Data analytics',
                'practitioner_qualification' => 'Pengalaman industri minimal 5 tahun',
                'partner_needs' => 'Perusahaan teknologi',
                'approval_status' => 'approved',
                'practitioner_status' => 'not_assigned',
                'partner_status' => 'not_assigned',
                'submitted_by' => $rinaUserId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $submissionId = DB::table('submissions')
            ->where('code', 'COURSES-DEMO-001')
            ->value('id');

        DB::table('schedules')->insert([
            [
                'submission_id' => $submissionId,
                'course_name' => 'Business Intelligence',
                'study_program' => 'Business Information System',
                'date' => now()->addDays(7)->toDateString(),
                'time' => '09:00–11:30',
                'location' => 'Ruang Kolaborasi 3',
                'is_published' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('documents')->insert([
            [
                'title' => 'Panduan Program PIJAR–SUAR',
                'category' => 'Panduan Akademik',
                'drive_url' => 'https://drive.google.com/',
                'description' => 'Dokumen referensi program.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Jalankan CourseSeeder untuk memasukkan data mata kuliah
        $this->call([
            CourseSeeder::class,
        ]);
    }
}

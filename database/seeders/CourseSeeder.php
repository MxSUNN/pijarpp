<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30113',
                'course_name' => 'Dasar Sistem Informasi',
                'credits' => 3,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30212',
                'course_name' => 'Algoritma dan Pemecahan Masalah',
                'credits' => 2,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30221',
                'course_name' => 'LAB Algoritma dan Pemecahan Masalah',
                'credits' => 1,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30613',
                'course_name' => 'Sistem Operasi',
                'credits' => 3,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30313',
                'course_name' => 'Matematika Dasar',
                'credits' => 3,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30413',
                'course_name' => 'Infrastruktur TIK',
                'credits' => 3,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'PR10112',
                'course_name' => 'AGAMA',
                'credits' => 2,
            ],
            [
                'study_program' => 'Business Information System',
                'course_code' => 'IS30513',
                'course_name' => 'Teori Sistem Perkotaan',
                'credits' => 3,
            ],
            [
                'study_program' => 'Informatic',
                'course_code' => 'LF177013',
                'course_name' => 'Teori TEKNOLOGY',
                'credits' => 3,
            ],
            
        ];

        foreach ($courses as $course) {
            DB::table('courses')->updateOrInsert(
                ['course_code' => $course['course_code']],
                $course + [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('study_program'); // Program studi (or foreignId if you have a separate table)
            $table->string('course_code')->unique(); // Kode mata kuliah
            $table->string('course_name'); // Nama mata kuliah
            $table->unsignedTinyInteger('credits'); // SKS (e.g., 2, 3, 4)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
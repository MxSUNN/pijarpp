<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teaching_materials', function (Blueprint $t) {
            $t->text('review_note')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('teaching_materials', function (Blueprint $t) {
            $t->dropColumn('review_note');
        });
    }
};
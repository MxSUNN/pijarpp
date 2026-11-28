<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

trait ScopesByRole
{
    /**
     * Batasi data sesuai role:
     * - kaprodi hanya melihat data prodinya
     * - dosen hanya melihat data miliknya
     */
    protected function scopedByRole(Builder $query, Request $request): Builder
    {
        $user = $request->user();

        if ($user->role === 'kaprodi') {
            $query->where('study_program', $user->study_program);
        }

        if ($user->role === 'dosen') {
            $query->where('lecturer_id', $user->lecturer_id);
        }

        return $query;
    }
}
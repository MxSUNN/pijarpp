<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Concerns\ScopesByRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseDashboardController extends Controller
{
    use ScopesByRole;

    protected function baseData(Request $request): array
    {
        $query = $this->scopedByRole(DB::table('submissions'), $request);

        return [
            'submissions' => (clone $query)->latest()->limit(5)->get(),
            'stats' => [
                'Pengajuan'    => (clone $query)->count(),
                'Disetujui'    => (clone $query)->where('approval_status', 'approved')->count(),
                'Perlu revisi' => (clone $query)->where('approval_status', 'revision_required')->count(),
                'Jadwal'       => DB::table('schedules')->count(),
            ],
        ];
    }
}
<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminKermaDashboardController extends BaseDashboardController
{
    public function index(Request $request)
    {
        return view('dashboard.admin_kerma', $this->baseData($request) + [
            // Pengajuan disetujui yang mitranya belum ditugaskan
            'needAssignment' => DB::table('submissions')
                ->where('approval_status', 'approved')
                ->where('partner_status', 'not_assigned')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
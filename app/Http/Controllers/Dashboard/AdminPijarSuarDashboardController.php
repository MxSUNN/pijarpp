<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPijarSuarDashboardController extends BaseDashboardController
{
    public function index(Request $request)
    {
        return view('dashboard.admin_pijar_suar', $this->baseData($request) + [
            // Pengajuan yang menunggu keputusan
            'pending' => DB::table('submissions')
                ->where('approval_status', 'submitted')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
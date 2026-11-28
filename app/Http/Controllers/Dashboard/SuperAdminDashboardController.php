<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends BaseDashboardController
{
    public function index(Request $request)
    {
        return view('dashboard.super_admin', $this->baseData($request) + [
            'totalUsers'  => DB::table('users')->count(),
            'recentAudit' => DB::table('audit_logs')->latest()->limit(5)->get(),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenDashboardController extends BaseDashboardController
{
    public function index(Request $request)
    {
        return view('dashboard.dosen', $this->baseData($request) + [
            'upcomingSchedules' => DB::table('schedules')->orderBy('date')->limit(5)->get(),
        ]);
    }
}
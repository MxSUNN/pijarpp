<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

class KaprodiDashboardController extends BaseDashboardController
{
    public function index(Request $request)
    {
        return view('dashboard.kaprodi', $this->baseData($request));
    }
}
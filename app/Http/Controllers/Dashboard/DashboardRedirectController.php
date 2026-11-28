<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request)
    {
        $role = $request->user()->role;

        abort_unless(
            in_array($role, ['super_admin', 'admin_pijar_suar', 'admin_kerma', 'kaprodi', 'dosen'], true),
            403
        );

        return redirect()->route('portal.dashboard.' . $role);
    }
}
<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /* switch ($user->role) {
            case 'admin':
                return view('dashboard.admin');
            case 'manajer':
                return view('dashboard.manager');
            case 'direktur':
                return view('dashboard.direktur');
            default:
                return view('dashboard.user');
        } */

        $user = $request->user();
        return view('dashboard.user', [
            'user' => $user,
            'role' => 'Nasabah',
        ]);
    }
}

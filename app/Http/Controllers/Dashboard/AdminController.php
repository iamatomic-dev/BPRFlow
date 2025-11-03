<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return view('dashboard.admin', [
            'user' => $user,
            'role' => 'Admin',
        ]);
    }
}

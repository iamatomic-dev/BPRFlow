<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Manager']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return view('dashboard.manager', [
            'user' => $user,
            'role' => 'Manager',
        ]);
    }
}

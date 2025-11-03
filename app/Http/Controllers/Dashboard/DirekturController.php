<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DirekturController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Direktur']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return view('dashboard.direktur', [
            'user' => $user,
            'role' => 'Direktur',
        ]);
    }
}

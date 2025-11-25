<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CreditApplication;

class RiwayatKreditController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $applications = CreditApplication::with('creditFacility')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('nasabah.riwayat.index', compact('applications'));
    }
    
    public function show($id)
    {
        $application = CreditApplication::with([
            'nasabahProfile', 
            'creditFacility', 
            'detail', 
            'collateral', 
            'documents'
        ])
        ->where('user_id', Auth::id())
        ->findOrFail($id);

        return view('nasabah.riwayat.show', compact('application'));
    }
}
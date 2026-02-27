<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\DataPerjalanan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $perjalanan = DataPerjalanan::with(['sppd.user.dataDiri'])
            ->whereHas('sppd', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->first();

        $sppd = $perjalanan->sppd;

        return view('dashboard', compact('user', 'sppd', 'perjalanan'));
    }

    public function history()
    {
        $user = Auth::user();

        $perjalanan = DataPerjalanan::with(['sppd'])
            ->whereHas('sppd', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest('created_at')
            ->paginate(10);

        $totalBiaya = DataPerjalanan::whereHas('sppd', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->sum('jumlah_sppd');

        return view('dashboard.history', compact('user', 'perjalanan', 'totalBiaya'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $perjalanan = DataPerjalanan::with(['sppd.user.dataDiri'])
            ->whereHas('sppd', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->findOrFail($id);

        if ($perjalanan->sppd->user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $sppd = $perjalanan->sppd;

        return view('dashboard.view-history', compact('user', 'sppd', 'perjalanan'));
    }
}

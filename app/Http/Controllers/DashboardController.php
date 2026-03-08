<?php

namespace App\Http\Controllers;

use App\Exports\SppdExport;
use App\Helpers\LampiranHelper;
use App\Models\DataPerjalanan;
use App\Models\DataSppd;
use App\Models\Lampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'dataDiri',
            'sppds' => function ($query) {
                $query->latest('created_at')->limit(1);
            }
        ]);

        $sppd = $user->sppds->first();

        if (! $sppd) {
            return view('dashboard', [
                'user' => $user,
                'sppd' => null,
                'perjalanan' => null,
                'message' => 'Anda belum memiliki data SPPD atau riwayat perjalanan dinas.',
                'messageType' => 'info',
            ]);
        }

        $perjalanan = DataPerjalanan::where('sppd_id', $sppd->id)->first();
        $lampiran = Lampiran::where('sppd_id', $sppd->id)->first();

        $sections = LampiranHelper::buildSections($lampiran);

        return view('dashboard', compact('user', 'sppd', 'perjalanan', 'sections'));
    }

    public function history()
    {
        $user = Auth::user();

        $perjalanan = DataPerjalanan::with(['sppd'])
            ->whereHas('sppd.users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->latest('created_at')
            ->paginate(10);

        $totalBiaya = DataPerjalanan::whereHas('sppd.users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
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

    public function export(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', null);

        $filename = 'SPPD_'.strtoupper(config('app.name', 'TVRI')).'_'.$year;

        if ($month) {
            $filename .= '_'.str_pad($month, 2, '0', STR_PAD_LEFT);
        }

        $filename .= '.xlsx';

        return Excel::download(new SppdExport($year, $month), $filename);
    }
}

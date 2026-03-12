<?php

namespace App\Http\Controllers;

use App\Exports\SppdExport;
use App\Helpers\LampiranHelper;
use App\Models\DataPerjalanan;
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
                $query->latest('data_sppd.created_at')->limit(1);
            },
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

        // CEK 1: Apakah Data Perjalanan ID tersebut benar-benar ada?
        $cekPerjalanan = DataPerjalanan::find($id);

        if (! $cekPerjalanan) {
            dd('DEBUG 1 GAGAL: Data Perjalanan dengan ID '.$id.' sama sekali TIDAK ADA di tabel data_perjalanan. Silakan buat data perjalanannya dulu di Filament.');
        }

        // CEK 2: Apakah perjalanan ini punya sambungan ke SPPD?
        $cekSppd = $cekPerjalanan->sppd;

        if (! $cekSppd) {
            dd('DEBUG 2 GAGAL: Perjalanan ID '.$id.' ADA, tapi kolom sppd_id-nya tidak terhubung ke tabel data_sppd manapun.');
        }

        // CEK 3: Intip isi tabel pivot, siapa saja pegawai di SPPD ini?
        $usersDiSppd = $cekSppd->users->pluck('id')->toArray();

        if (! in_array($user->id, $usersDiSppd)) {
            dd([
                'STATUS' => 'DEBUG 3 GAGAL: Anda terblokir sistem keamanan.',
                'ALASAN' => 'ID Anda tidak terdaftar sebagai rombongan di SPPD ini.',
                'ID AKUN ANDA SAAT INI' => $user->id,
                'DAFTAR ID PEGAWAI DI SPPD INI' => empty($usersDiSppd) ? 'KOSONG (Belum ada pegawai yang dipilih)' : $usersDiSppd,
                'SOLUSI' => 'Buka Filament Admin > Edit SPPD terkait > Masukkan nama Anda di form "Pegawai yang Ditugaskan" > Simpan.',
            ]);
        }

        // --- Jika lolos 3 tahap di atas, jalankan kode asli ---
        $perjalanan = DataPerjalanan::with(['sppd.users.dataDiri'])->findOrFail($id);
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

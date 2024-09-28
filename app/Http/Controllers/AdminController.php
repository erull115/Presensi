<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $hariini = date("Y-m-d");
        $approved = 0;
        // $rekapabsensi = DB::table('absensi')
        //     ->selectRaw('COUNT(nip) as jmlhadir, SUM(if ( jam_in > "08:00",1,0)) as jmltelat')
        //     ->where('tgl_absensi',$hariini)
        //     ->first();
        if ($user->hasRole('Admin')) {
            $rekapizin = DB::table('buat_izin')
                ->selectRaw('COUNT(buat_izin.nip) as jmlizin')
                ->join('guru', 'buat_izin.nip', '=', 'guru.nip')
                ->where('status_approved', $approved)
                ->where('guru.kode_jenjang', $kode_jenjang)
                ->first();
            $rekapsakit = DB::table('buat_sakit')
                ->selectRaw('COUNT(buat_sakit.nip) as jmlsakit')
                ->join('guru', 'buat_sakit.nip', '=', 'guru.nip')
                ->where('status_approved', $approved)
                ->where('guru.kode_jenjang', $kode_jenjang)
                ->first();
            $rekapcuti = DB::table('buat_cuti')
                ->selectRaw('COUNT(buat_cuti.nip) as jmlcuti')
                ->join('guru', 'buat_cuti.nip', '=', 'guru.nip')
                ->where('status_approved', $approved)
                ->where('guru.kode_jenjang', $kode_jenjang)
                ->first();
        } else {
            $rekapizin = DB::table('buat_izin')
                ->selectRaw('COUNT(nip) as jmlizin')
                ->where('status_approved', $approved)
                ->first();
            $rekapsakit = DB::table('buat_sakit')
                ->selectRaw('COUNT(nip) as jmlsakit')
                ->where('status_approved', $approved)
                ->first();
            $rekapcuti = DB::table('buat_cuti')
                ->selectRaw('COUNT(nip) as jmlcuti')
                ->where('status_approved', $approved)
                ->first();
        }

        return view('dashboard.admindashboard', compact('rekapizin', 'rekapsakit', 'rekapcuti'));
    }
}

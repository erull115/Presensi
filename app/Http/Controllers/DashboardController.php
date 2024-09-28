<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $hariini = date("Y-m-d");
        $nip = Auth::guard('guru')->user()->nip;

        $absensiHariIni = DB::table('absensi')
            ->where('nip', $nip)
            ->where('tgl_absensi', $hariini)
            ->orderBy('jam_in', 'desc')
            ->first();

        $historibulanini = DB::table('absensi')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_absensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_absensi)="' . $tahunini . '"')
            ->orderBy('tgl_absensi', 'desc')
            ->orderBy('jam_in', 'desc')
            ->get();

        $rekapabsensi = DB::table('absensi')
            ->selectRaw('COUNT(jam_out) as jmlhadir')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_absensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_absensi)="' . $tahunini . '"')
            ->first();
        $rekapizin = DB::table('buat_izin')
            ->selectRaw('COUNT(kode_izin) as jmlizin')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->whereRaw('MONTH(tanggal1)="' . $bulanini . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahunini . '"')
            ->first();
        $rekapsakit = DB::table('buat_sakit')
            ->selectRaw('COUNT(kode_izin) as jmlsakit')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->whereRaw('MONTH(tanggal1)="' . $bulanini . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahunini . '"')
            ->first();
        $rekapcuti = DB::table('buat_cuti')
            ->selectRaw('COUNT(kode_izin) as jmlcuti')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->whereRaw('MONTH(tanggal1)="' . $bulanini . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahunini . '"')
            ->first();

        $leaderboard = DB::table('absensi')
            ->join('guru', 'guru.nip', "=", 'absensi.nip')
            ->where('tgl_absensi', $hariini)
            ->orderBy('jam_in')
            ->get();
        $datahistoribuatganti = DB::table('buat_ganti')
            ->where('nip', $nip)
            ->get();

        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        return view("dashboard.dashboard", compact(
            'absensiHariIni',
            'historibulanini',
            'namabulan',
            'bulanini',
            'tahunini',
            'rekapabsensi',
            'leaderboard',
            'datahistoribuatganti',
            'rekapizin',
            'rekapsakit',
            'rekapcuti'
        ));
    }
}
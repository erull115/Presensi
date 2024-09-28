<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class laporanController extends Controller
{
    public function laporanabsensi()
    {
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
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
        if ($user->hasRole('Admin')) {
            $guru = DB::table('guru')
                ->where('guru.kode_jenjang', $kode_jenjang)
                ->orderBy('nip')->get();
        } else if ($user->hasRole('Super User')) {
            $guru = DB::table('guru')
                ->orderBy('nip')->get();
        }

        return view('absensi.laporanabsensi', compact('namabulan', 'guru'));
    }
    public function cetakabsensi(Request $request)
    {
        $nip = $request->nip;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $guru = DB::table('guru')
            ->leftJoin('jenjang', 'guru.kode_jenjang', '=', 'jenjang.kode_jenjang')
            ->where('nip', $nip)
            ->first();

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

        $absensi = DB::table('absensi')
            ->where('nip', $nip)
        //->leftJoin('jadwal', 'absensi.nip','=','jadwal.nip')
            ->whereRaw('MONTH(tgl_absensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_absensi)="' . $tahun . '"')
            ->get();
        $totabsensi = DB::table('absensi')
            ->selectRaw('SUM(jml_jp) as "Total_absen"')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_absensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_absensi)="' . $tahun . '"')
            ->first();
        $ganti = DB::table('buat_ganti')
            ->join('buat_izin', 'buat_ganti.kode_izin', '=', 'buat_izin.kode_izin')
            ->where('buat_ganti.nip', $nip)
            ->whereRaw('MONTH(tanggal)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
            ->get();
        $totganti = DB::table('buat_ganti')
            ->selectRaw('SUM(tot_jam) as "Total"')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->first();
        $totizin = DB::table('buat_izin')
            ->selectRaw('SUM(jml_jp) as "Total_izin"')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->first();
        $total = $totabsensi->Total_absen + $totganti->Total;
        if (isset($_POST['export'])) {
            $time = date("H:i:s");
            header("Content-type: application/vnd-ms-excel");

            header("Content-Disposition: attachment; filename=Laporan Absensi $nip.xls");

            return view('absensi.cetakabsensiexcel', compact('namabulan', 'bulan', 'tahun', 'guru', 'absensi', 'ganti', 'totabsensi', 'totganti', 'total'));
        }
        return view('absensi.cetakabsensi', compact('namabulan', 'bulan', 'tahun', 'guru', 'absensi', 'ganti', 'totabsensi', 'totganti', 'total'));
    }
}
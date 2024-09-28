<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class monitoringController extends Controller
{
    public function monitoring()
    {
        return view('monitoring.monitoring');
    }
    public function getabsensi(Request $request)
    {
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);

        $tanggal = $request->tanggal;
        if ($user->hasRole('Admin|Kepsek|Wakur')) {
            $absensi = DB::table('absensi')
                ->select('absensi.*', 'nama_lengkap', 'kode_jenjang')
                ->join('guru', 'absensi.nip', '=', 'guru.nip')
                ->where('guru.kode_jenjang', $kode_jenjang)
                ->where('tgl_absensi', $tanggal)
                ->get();
        } else if ($user->hasRole('Super User')) {
            $absensi = DB::table('absensi')
                ->select('absensi.*', 'nama_lengkap', 'kode_jenjang')
                ->join('guru', 'absensi.nip', '=', 'guru.nip')
                ->where('tgl_absensi', $tanggal)
                ->get();
        }

        return view('monitoring.getabsensi', compact('absensi', 'tanggal'));
    }
    public function gethari()
    {
        $hari = date("D");
        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;
            case 'Mon':
                $hari_ini = "Senin";
                break;
            case 'Tue':
                $hari_ini = "Selasa";
                break;
            case 'Wed':
                $hari_ini = "Rabu";
                break;
            case 'Thu':
                $hari_ini = "Kamis";
                break;
            case 'Fri':
                $hari_ini = "Jumat";
                break;
            case 'Sat':
                $hari_ini = "Sabtu";
                break;
            default:
                $hari_ini = "Tidak Diketahui";
        }
        return $hari_ini;
    }
    public function koreksiabsensi(Request $request)
    {

        $nip = $request->nip;
        $guru = DB::table('guru')
            ->where('nip', $nip)
            ->first();
        $tanggal = $request->tanggal;
        $cekabsen = DB::table('absensi')
            ->where('nip', $nip)
            ->where('tgl_absensi', $tanggal)
            ->first();

        return view('monitoring.koreksiabsensi', compact('guru', 'tanggal', 'cekabsen'));
    }

    public function koreksikehadiran(Request $request)
    {
        $namahari = $this->gethari();
        $nip = $request->nip;
        $tanggal = $request->tanggal;
        $jp = $request->jp;
        $jam_out = $request->jam_out;

        $cekjadwal = DB::table('jadwal')
            ->where('nip', $nip)
            ->where('hari', $namahari)
            ->first();

        if ($jp > $cekjadwal->jp) {
            return Redirect::back()->with(['error' => 'Maaf Jumlah Jam Pelajaran Melebihi jadwal']);
            // } else if ($jp < $cekjadwal->jp) {
            //     return Redirect::back()->with(['error' => 'Maaf Jumlah Jam Pelajaran Kurang dari jadwal']);
        } else {
            try {
                DB::table('absensi')
                    ->where('nip', $nip)
                    ->where('tgl_absensi', $tanggal)
                    ->update([
                        'jml_jp' => $jp,
                        'jam_out' => $jam_out,
                    ]);
                return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
            } catch (\Exception $e) {
                return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
            }
        }
    }
    public function deleteabsen(Request $request, $nip, $tanggal)
    {
        try {
            DB::table('absensi')
                ->where('nip', $nip)
                ->where('tgl_absensi', $tanggal)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Di Hapus']);
        }
    }
}

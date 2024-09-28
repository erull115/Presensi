<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class konfigurasiController extends Controller
{
    public function jadwal(Request $request)
    {
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $query = Guru::query();
        $query->select('guru.*', 'nama_lengkap', 'jadwal.*');
        $query->join('jadwal', 'guru.nip', '=', 'jadwal.nip');
        $query->orderBy('jadwal.nip');
        $query->orderBy('id');

        if (!empty($request->nama_guru)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_guru . '%');
        }
        if (!empty($request->kode_jenjang)) {
            $query->where('guru.kode_jenjang', $request->kode_jenjang);
        }
        if ($user->hasRole('Admin')) {
            $query->where('guru.kode_jenjang', $kode_jenjang);
        }
        $jadwal = $query->paginate(50);
        $jenjang = DB::table('jenjang')->get();

        return view('konfigurasi.jadwal', compact('jadwal', 'jenjang'));
    }
    public function setjamkerja($nip)
    {
        $nip = Crypt::decrypt($nip);
        $guru = DB::table('guru')->where('nip', $nip)->first();
        $jadwal = DB::table('jadwal')
            ->where('nip', $nip)
            ->orderBy('id')
            ->get();
        return view('konfigurasi.setjamkerja', compact('guru', 'jadwal'));
    }
    public function jadwaljamkerja(Request $request)
    {
        $nip = $request->nip;
        $hari = $request->hari;
        $jam1_in = $request->jam1_in;
        $jam1_out = $request->jam1_out;
        $jp = $request->jp;

        $data = [
            'nip' => $nip,
            'hari' => $hari,
            'jp1_in' => $jam1_in,
            'jp1_out' => $jam1_out,
            'jp' => $jp,

        ];
        $sethari = $hari = $request->hari;
        $cek = DB::table('jadwal')
            ->where('hari', $sethari)
            ->where('nip', $nip)
            ->count();
        if ($cek > 0) {
            return Redirect::back()->with(['error' => "Maaf jam kerja sudah disetting"]);
        } else {
            $simpan = DB::table('jadwal')->insert($data);
            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            } else {
                return Redirect::back()->with(['error' => 'Data Belum Disimpan']);
            }
        }
    }
    public function deletejadwal($nip, $hari)
    {
        $delete = DB::table('jadwal')->where('nip', $nip)->where('hari', $hari)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Delete']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Delete']);
        }
    }
    public function lokasikantor()
    {
        $lokasi_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('lokasi.lokasi', compact('lokasi_kantor'));
    }
    public function updatelokasi(Request $request)
    {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        try {
            DB::table('konfigurasi_lokasi')->where('id', 1)->update([
                'lokasi' => $lokasi_kantor,
                'radius' => $radius,
            ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
            //throw $th;
        }
    }
}
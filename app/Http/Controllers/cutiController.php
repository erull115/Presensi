<?php

namespace App\Http\Controllers;

use App\Models\cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class cutiController extends Controller
{
    public function cuti()
    {
        $mastercuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('cuti.form_cuti', compact('mastercuti'));
    }
    public function buat_izin_cuti(Request $request)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $tanggal1 = $request->tanggal1;
        $tanggal2 = $request->tanggal2;
        $jml_hari = $request->jml_hari;
        $status = "c";
        $kode_cuti = $request->kode_cuti;
        $keterangan = $request->keterangan;

        $bulan = date("m", strtotime($tanggal1));
        $tahun = date("Y", strtotime($tanggal1));
        $thn = substr($tahun, 2, 2);

        $izinakhir = DB::table('buat_cuti')
            ->whereRaw('MONTH(tanggal1)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->first();

        $lastkodeizin = $izinakhir != null ? $izinakhir->kode_izin : "";
        $format = "CT-" . $bulan . $thn . "-";
        $kode_izin = buatkode($lastkodeizin, $format, 3);
        $data = [
            'nip' => $nip,
            'kode_izin' => $kode_izin,
            'kode_cuti' => $kode_cuti,
            'tanggal1' => $tanggal1,
            'tanggal2' => $tanggal2,
            'jml_hari' => $jml_hari,
            'status' => $status,
            'keterangan' => $keterangan,

        ];
        $simpan = DB::table('buat_cuti')->insert($data);

        if ($simpan) {
            return redirect('/izin/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/izin/izin')->with(['success' => 'Data Berhasil Disimpan']);
        }
    }

    //admin
    public function pengajuancuti(Request $request)
    {
        $tanggal = date('Y-m-d');
        $query = cuti::query();
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $query->select('buat_cuti.*', 'nama_lengkap', 'kode_jenjang');
        $query->join('guru', 'buat_cuti.nip', '=', 'guru.nip');
        $query->orderBy('kode_izin', 'desc');

        if (!empty($request->kode_izin)) {
            $query->where('kode_izin', 'like', '%' . $request->kode_izin . '%');
        }
        if ($user->hasRole('Admin')) {
            $query->where('guru.kode_jenjang', $kode_jenjang);
        }
        $cuti = $query->paginate(10);
        return view('cuti.pengajuancuti', compact('cuti', 'tanggal'));
    }
    public function datapengajuancuti(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_cuti_form = $request->id_cuti_form;
        if ($status_approved == 2) {
            $delete = DB::table('buat_cuti')->where('kode_izin', $id_cuti_form)->delete();
            if ($delete) {
                return Redirect::back()->with(['error' => 'Data Telah Ditolak']);
            } else {
                return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
            }
        } else {
            $update = DB::table('buat_cuti')->where('kode_izin', $id_cuti_form)->update([
                'status_approved' => $status_approved,
            ]);
        }
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Telah Disetujui']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
        }
    }
    public function batalcuti($kode_izin)
    {
        $update = DB::table('buat_cuti')->where('kode_izin', $kode_izin)->update([
            'status_approved' => 0,
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Belum Disimpan']);
        }
    }
}
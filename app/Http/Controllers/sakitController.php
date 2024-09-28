<?php

namespace App\Http\Controllers;

use App\Models\sakit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class sakitController extends Controller
{
    public function sakit()
    {

        return view('sakit.form_sakit');
    }
    public function buat_izin_sakit(Request $request)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $hariini = date("Y-m-d");
        $tanggal1 = $request->tanggal1;
        $tanggal2 = $request->tanggal2;
        $jml_hari = $request->jml_hari;
        $status = "s";
        $keterangan = $request->keterangan;

        $bulan = date("m", strtotime($tanggal1));
        $tahun = date("Y", strtotime($tanggal1));
        $thn = substr($tahun, 2, 2);

        $izinakhir = DB::table('buat_sakit')
            ->whereRaw('MONTH(tanggal1)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->first();

        $lastkodeizin = $izinakhir != null ? $izinakhir->kode_izin : "";
        $format = "SA-" . $bulan . $thn . "-";
        $kode_izin = buatkode($lastkodeizin, $format, 3);

        if ($request->hasFile('sid')) {
            $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
        } else {
            $sid = null;
        }

        $data = [
            'nip' => $nip,
            'kode_izin' => $kode_izin,
            'tanggal1' => $tanggal1,
            'tanggal2' => $tanggal2,
            'jml_hari' => $jml_hari,
            'status' => $status,
            'keterangan' => $keterangan,
            'doc_sid' => $sid,

        ];
        $ceksakit = DB::table('buat_sakit')
            ->select('tanggal2')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->first();
        if ($ceksakit == null) {
            $simpan = DB::table('buat_sakit')->insert($data);

            if ($simpan) {
                if ($request->hasFile('sid')) {
                    $sid = $kode_izin . "." . $request->file('sid')->getClientOriginalExtension();
                    $folderPath = "public/uploads/sid/";
                    $request->file('sid')->storeAs($folderPath, $sid);
                }
                return redirect('/izin/izin')->with(['success' => 'Data Berhasil Disimpan']);
            } else {
                return redirect('/izin/izin')->with(['error' => 'Data Gagal Disimpan']);
            }
        } elseif ($hariini < $ceksakit->tanggal2) {
            return redirect('/izin/izin')->with(['error' => 'Maaf anda sudah mengajukan Izin Sakit']);
        }
    }
    public function pengajuan_sakit(Request $request)
    {
        $query = sakit::query();
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $query->select('buat_sakit.*', 'nama_lengkap', 'kode_jenjang');
        $query->join('guru', 'buat_sakit.nip', '=', 'guru.nip');
        $query->orderBy('kode_izin', 'desc');

        if (!empty($request->kode_izin)) {
            $query->where('kode_izin', 'like', '%' . $request->kode_izin . '%');
        }
        if ($user->hasRole('Admin')) {
            $query->where('guru.kode_jenjang', $kode_jenjang);
        }
        $sakit = $query->paginate(10);
        return view('sakit.pengajuan_sakit', compact('sakit'));
    }
    public function datapengajuansakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_sakit_form = $request->id_sakit_form;
        if ($status_approved == 2) {
            try {
                DB::table('buat_sakit')->where('kode_izin', $id_sakit_form)->delete();
                return Redirect::back()->with(['error' => 'Data Telah Ditolak']);
            } catch (\Exception $e) {
                return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
            }
        } else {
            $update = DB::table('buat_sakit')->where('kode_izin', $id_sakit_form)->update([
                'status_approved' => $status_approved,
            ]);
        }
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Telah Disetujui']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
        }
    }
    public function batalsakit($kode_izin)
    {
        $cek = DB::table('buat_sakit')
            ->join('buat_ganti', 'buat_sakit.kode_izin', '=', 'buat_ganti.kode_sakit')
            ->where('buat_sakit.kode_izin', $kode_izin)
            ->count();
        try {
            if ($cek > 0) {
                return Redirect::back()->with(['error' => 'Data tidak bisa dirubah, sudah ada pengganti']);
            } else {
                DB::table('buat_sakit')->where('kode_izin', $kode_izin)->update([
                    'status_approved' => 0,
                ]);
                return Redirect::back()->with(['success' => 'Data Berhasil Dirubah']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal Dirubah']);
        }

    }
}
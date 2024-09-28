<?php

namespace App\Http\Controllers;

use App\Models\izin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinController extends Controller
{
    //
    public function izin()
    {

        $nip = Auth::guard('guru')->user()->nip;
        $tanggal = date('Y-m-d');
        $bulan = date("m", strtotime($tanggal));
        $tahun = date("Y", strtotime($tanggal));

        //histori cuti
        $datahistoribuatcuti = DB::table('buat_cuti')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tanggal1)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->get();
        //histori izin
        $datahistoribuatizin = DB::table('buat_izin')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tanggal1)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->get();
        //histori sakit
        $datahistoribuatsakit = DB::table('buat_sakit')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tanggal1)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->get();
        //ganti jam
        // $datahistoribuatganti = DB::table('buat_ganti')
        //     ->where('nip', $nip)
        //     ->get();
        return view('izin.izin', compact('datahistoribuatcuti', 'datahistoribuatizin', 'datahistoribuatsakit', 'tanggal'));
    }
    public function izinabsen()
    {
        $tanggal = date('Y-m-d');
        $nip = Auth::guard('guru')->user()->nip;
        $cek = DB::table('absensi')
            ->where('nip', $nip)
            ->where('tgl_absensi', $tanggal)
            ->count();
        // if ($cek > 0) {
        return view('izin.form_izin', compact('tanggal'));
        // } else {
        //     return redirect('/izin/izin')->with(['error' => 'Maaf !! Anda hari ini belum absen']);
        // }
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
    public function buat_izin(Request $request)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $tanggal1 = date("Y-m-d");
        $namahari = $this->gethari();
        $jam1 = $request->jam1;
        $jam2 = $request->jam2;
        $jp = $request->jp;
        $status = "i";
        $keterangan = $request->keterangan;

        $bulan = date("m", strtotime($tanggal1));
        $tahun = date("Y", strtotime($tanggal1));
        $thn = substr($tahun, 2, 2);

        $jam_1 = date("H:m", strtotime($jam1));
        $jam_2 = date("H:m", strtotime($jam2));

        $izinakhir = DB::table('buat_izin')
            ->whereRaw('MONTH(tanggal1)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal1)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->first();

        $lastkodeizin = $izinakhir != null ? $izinakhir->kode_izin : "";
        $format = "IZ-" . $bulan . $thn . "-";
        $kode_izin = buatkode($lastkodeizin, $format, 3);
        $cek = DB::table('buat_izin')->where('nip', $nip)->where('tanggal1', $tanggal1)->count();
        $cekjadwal = DB::table('jadwal')
            ->where('nip', $nip)
            ->where('hari', $namahari)
            ->first();

        if ($cek > 0) {
            return redirect('/izin/izin')->with(['error' => 'Maaf !! Anda hari ini sudah membuat pengajuan izin']);
        } else if ($cekjadwal == null) {
            return redirect('/izin/izin')->with(['warning' => 'Maaf anda tidak mempunyai jadwal hari ini, hubungi admin!!']);
        } else if ($jp > $cekjadwal->jp) {
            return redirect('/izin/izin')->with(['error' => 'Maaf Jumlah Jam Pelajaran Melebihi jadwal']);
        } else if ($jam2 > $cekjadwal->jp1_out) {
            return redirect('/izin/izin')->with(['error' => 'Maaf Jumlah Jam Pengajuan Melebihi jadwal']);
        } else {
            $data = [
                'nip' => $nip,
                'kode_izin' => $kode_izin,
                'tanggal1' => $tanggal1,
                'jam1' => $jam_1,
                'jam2' => $jam_2,
                'jml_jp' => $jp,
                'status' => $status,
                'keterangan' => $keterangan,

            ];
            $simpan = DB::table('buat_izin')->insert($data);
        }

        if ($simpan) {
            return redirect('/izin/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/izin/izin')->with(['error' => 'Data gagal Disimpan']);
        }
    }

    public function pengajuanizin(Request $request)
    {
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $query = izin::query();
        $query->select('buat_izin.*', 'nama_lengkap', 'kode_jenjang');
        $query->join('guru', 'buat_izin.nip', '=', 'guru.nip');
        $query->orderBy('tanggal1', 'desc');

        if (!empty($request->kode_izin)) {
            $query->where('kode_izin', 'like', '%' . $request->kode_izin . '%');
        }
        if ($user->hasRole('Admin')) {
            $query->where('guru.kode_jenjang', $kode_jenjang);
        }
        $izin = $query->paginate(10);
        return view('izin.pengajuanizin', compact('izin'));
    }
    public function datapengajuanizin(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izin_form = $request->id_izin_form;
        if ($status_approved == 2) {
            $delete = DB::table('buat_izin')->where('kode_izin', $id_izin_form)->delete();
            if ($delete) {
                return Redirect::back()->with(['error' => 'Data Telah Ditolak']);
            } else {
                return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
            }
        } else {
            $update = DB::table('buat_izin')->where('kode_izin', $id_izin_form)->update([
                'status_approved' => $status_approved,
            ]);
        }
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Telah Disetujui']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
        }
    }
    public function batalizin($kode_izin)
    {
        $cek = DB::table('buat_izin')
            ->join('buat_ganti', 'buat_izin.kode_izin', '=', 'buat_ganti.kode_izin')
            ->where('buat_izin.kode_izin', $kode_izin)
            ->count();
        try {
            if ($cek > 0) {
                return Redirect::back()->with(['error' => 'Data tidak bisa dirubah, sudah ada pengganti']);
            } else {
                DB::table('buat_izin')->where('kode_izin', $kode_izin)->update([
                    'status_approved' => 0,
                ]);
                return Redirect::back()->with(['success' => 'Data Berhasil Dirubah']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal Dirubah']);
        }
    }
}

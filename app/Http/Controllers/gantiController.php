<?php

namespace App\Http\Controllers;

use App\Models\ganti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class gantiController extends Controller
{
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
    public function ganti()
    {
        $namahari = $this->gethari();
        $nip = Auth::guard('guru')->user()->nip;
        $kode_jenjang = Auth::guard('guru')->user()->kode_jenjang;
        $tanggal = date('Y-m-d');

        $cekizin = DB::table('buat_izin')
            ->where('tanggal1', $tanggal)
            ->where('nip',$nip)
            ->count();
        $datagantijam = DB::table('buat_izin')
            ->join('guru', 'buat_izin.nip', '=', 'guru.nip')
            ->where('kode_jenjang', $kode_jenjang)
            ->where('tanggal1', $tanggal)
            ->where('status_approved', 1)
            ->get();
        $sakit = DB::table('buat_sakit')
            ->join('guru', 'buat_sakit.nip', '=', 'guru.nip')
            ->join('jadwal', 'buat_sakit.nip', '=', 'jadwal.nip')
            ->where('kode_jenjang', $kode_jenjang)
            ->where('hari', $namahari)
            ->where('status_approved', 1)
            ->get();
        return view('ganti.form_ganti', compact('datagantijam', 'sakit', 'tanggal', 'nip','cekizin'));
    }

    public function pengajuan_ganti($kode_izin, $status)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $namahari = $this->gethari();
        $kode_izin = Crypt::decrypt($kode_izin);
        $guru = DB::table('guru')
            ->where('nip', $nip)
            ->first();
        $tanggal = date('Y-m-d');
        $data_pengajuan = DB::table('buat_izin')
            ->join('guru', 'buat_izin.nip', '=', 'guru.nip')
            ->where('kode_izin', $kode_izin)
            ->where('tanggal1', $tanggal)
            ->where('status_approved', 1)
            ->get();
        $sakit = DB::table('buat_sakit')
            ->join('guru', 'buat_sakit.nip', '=', 'guru.nip')
            ->where('kode_izin', $kode_izin)
            ->join('jadwal', 'buat_sakit.nip', '=', 'jadwal.nip')
            ->where('hari', $namahari)
            ->where('status_approved', 1)
            ->get();

        return view('ganti.pengajuan_ganti', compact('data_pengajuan', 'sakit', 'guru', 'status'));
    }
    public function buat_ganti(Request $request)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $ganti = $request->ganti;
        $tanggal1 = date("Y-m-d");
        $kode_izin = $request->kode_izin;
        $jml_jp = $request->jml_jp;
        $status = $request->status;
        $statusganti = "m";

        $bulan = date("m", strtotime($tanggal1));
        $tahun = date("Y", strtotime($tanggal1));
        $thn = substr($tahun, 2, 2);

        $gantiakhir = DB::table('buat_ganti')
            ->whereRaw('MONTH(tanggal)="' . $bulan . '"')
            ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
            ->orderBy('kode_ganti','desc')
            ->first();

        $lastkodeganti = $gantiakhir != null ? $gantiakhir->kode_ganti : "";
        $format = "GT-" . $bulan . $thn . "-";
        $kode_ganti = buatkode($lastkodeganti, $format, 3);
        $cek = DB::table('buat_ganti')
            ->where('nip',$nip)
            ->where('tanggal',$tanggal1)
            ->count();
        $cek2 = DB::table('buat_ganti')
            ->where('kode_izin', $kode_izin)
            ->where('tanggal',$tanggal1)
            ->count();
        $cek3 = DB::table('buat_ganti')
            ->where('kode_sakit', $kode_izin)
            ->where('tanggal',$tanggal1)
            ->count();

        try {
            if ($cek > 0) {
                return redirect('/ganti/form_ganti')->with(['warning' => 'Maaf anda melebihi mengajukan hal ini']);
            } elseif ($cek2 > 0) {
                return redirect('/ganti/form_ganti')->with(['warning' => 'Maaf sudah ada yang mengajukan hal ini']);
            }elseif ($cek3 > 0) {
                return redirect('/ganti/form_ganti')->with(['warning' => 'Maaf sudah ada yang mengajukan hal ini']);
            } elseif ($status == "i") {
                $data = [
                    'nip' => $nip,
                    'kode_ganti' => $kode_ganti,
                    'kode_izin' => $kode_izin,
                    'tanggal' => $tanggal1,
                    'ganti' => $ganti,
                    'tot_jam' => $jml_jp,
                    'status' => $statusganti,
                ];
            } elseif ($status == "s") {
                $data = [
                    'nip' => $nip,
                    'kode_ganti' => $kode_ganti,
                    'kode_sakit' => $kode_izin,
                    'tanggal' => $tanggal1,
                    'ganti' => $ganti,
                    'tot_jam' => $jml_jp,
                    'status' => $statusganti,
                ];
            }
            DB::table('buat_ganti')->insert($data);
            return redirect('/ganti/form_ganti')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return redirect('/ganti/form_ganti')->with(['error' => 'Data gagal Disimpan']);
        }
    }

    public function datapergantianjam(Request $request)
    {
        $query = ganti::query();
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $query->select('buat_ganti.*', 'nama_lengkap', 'kode_jenjang');
        $query->join('guru', 'buat_ganti.nip', '=', 'guru.nip');
        $query->orderBy('tanggal', 'desc');

        if (!empty($request->kode_ganti)) {
            $query->where('kode_ganti', 'like', '%' . $request->kode_ganti . '%');
        }
        if ($user->hasRole('Admin')) {
            $query->where('guru.kode_jenjang', $kode_jenjang);
        }
        $ganti = $query->paginate(10);
        return view('ganti.data_pergantian_jam', compact('ganti'));
    }
    public function datapengajuanganti(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_ganti_form = $request->id_ganti_form;
        if ($status_approved == 2) {
            $delete = DB::table('buat_ganti')->where('kode_ganti', $id_ganti_form)->delete();
            if ($delete) {
                return Redirect::back()->with(['error' => 'Data Telah Ditolak']);
            } else {
                return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
            }
        } else {
            $update = DB::table('buat_ganti')->where('kode_ganti', $id_ganti_form)->update([
                'status_approved' => $status_approved,
            ]);
        }
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Telah Disetujui']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disimpan']);
        }
    }
    public function batalganti($kode_ganti)
    {
        $update = DB::table('buat_ganti')->where('kode_ganti', $kode_ganti)->update([
            'status_approved' => 0,
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Belum Disimpan']);
        }
    }
}

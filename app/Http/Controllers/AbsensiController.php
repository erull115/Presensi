<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
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
    public function pilihabsen()
    {
        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $nip = Auth::guard('guru')->user()->nip;
        $jadwalhariini = DB::table('jadwal')
            ->where('nip', $nip)
            ->where('hari', $namahari)
            ->get();
        $jadwalini = DB::table('jadwal')
            ->where('nip', $nip)
            ->where('hari', $namahari)
            ->count();

        if ($jadwalini > 0) {
            return view('Absensi.pilihjam', compact('jadwalhariini'));
        } else {
            return view('Absensi.notifabsen');
        }
    }

    public function create()
    {
        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $nip = Auth::guard('guru')->user()->nip;

        $jammapel = DB::table('jadwal')
            ->where('nip', $nip)
            ->where('hari', $namahari)
            ->first();

        $cek = DB::table('absensi')
            ->where('tgl_absensi', $hariini)
            ->where('nip', $nip)
            ->count();
        $lokasi_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $cekizin = DB::table('buat_izin')->where('nip', $nip)->where('tanggal1', $hariini)->where('status_approved', 1)->count();
        $ceksakit = DB::table('buat_sakit')
            ->select('tanggal1', 'tanggal2')
            ->where('nip', $nip)
            ->where('status_approved', 1)
            ->first();
        if ($cekizin > 0) {
            return view('izin.notifizin');
        } elseif ($ceksakit == null) {
            return view('Absensi.create', compact('cek', 'jammapel', 'lokasi_kantor'));
        } elseif ($hariini >= $ceksakit->tanggal1 && $hariini <= $ceksakit->tanggal2) {
            return view('izin.notifizin');
        }
        return view('Absensi.create', compact('cek', 'jammapel', 'lokasi_kantor'));
    }

    public function store(Request $request)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok_kantor = explode(",", $kantor->lokasi);
        $tgl_absensi = date("Y-m-d");
        $jam = date("H:i:s");
        $latitudekandor = $lok_kantor[0];
        $longitudekantor = $lok_kantor[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekandor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('absensi')
            ->where('tgl_absensi', $tgl_absensi)
            ->where('nip', $nip)
            ->count();

        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/presensi/";
        $formatName = $nip . "-" . $tgl_absensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;
        $namahari = $this->gethari();

        if ($radius > $kantor->radius) {
            echo "error | Maaf anda diluar kantor, " . $radius . " meter dari kantor|radius";
        } else {
            $batasjp = DB::table('jadwal')
                ->where('hari', $namahari)
                ->where('nip', $nip)
                ->first();
            $jml_jp = $batasjp->jp;
            $cek = DB::table('absensi')
                ->where('tgl_absensi', $tgl_absensi)
                ->where('nip', $nip)
                ->count();

            if ($cek > 0) {
                //jam selesai jp 1
                if ($jam < $batasjp->jp1_out) {
                    echo " error| Maaf Belum Saatnya Jam Pulang";
                } elseif ($jam > "17:00") {
                    echo " error| Maaf Hari ini sudah absen";
                } else {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'jml_jp' => $jml_jp,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi,
                    ];
                    $update = DB::table('absensi')
                        ->where('tgl_absensi', $tgl_absensi)
                        ->where('nip', $nip)
                        ->update($data_pulang);
                    if ($update) {
                        echo "success|Terimakasih, Hati - hati dijalan |out";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Absensi Gagal Hubungi Nata|out";
                    }
                }
            } else {
                //jam mulai jp 1
                if ($jam < $batasjp->jp1_in) {
                    echo " error| Maaf belum saatnya absen masuk !!!";
                } else {
                    $data = [
                        'nip' => $nip,
                        'tgl_absensi' => $tgl_absensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                    ];
                    $simpan = DB::table('absensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terimaksih, Selamat Mengajar|in";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Absensi Gagal Hubungi Nata|in";
                    }
                }
            }
        }
    }

    //Menghitung Jarak
    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofil()
    {
        $nip = Auth::guard('guru')->user()->nip;
        $edituser = DB::table('guru')
            ->where('nip', $nip)
            ->first();
        return view('absensi.editprofil', compact('edituser'));
    }

    //proses update profile
    public function updateprofile(Request $request)
    {
        $nip = Auth::guard('guru')->user()->nip;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $old_foto = $request->old_foto;

        $request->validate([
            'foto' => 'image|mimes:jpg,jpeg|max:500',
        ]);
        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto,
            ];
        }
        $update = DB::table('guru')->where('nip', $nip)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPathOld = "public/uploads/guru/" . $old_foto;
                Storage::delete($folderPathOld);
                $folderPath = "public/uploads/guru/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }
}
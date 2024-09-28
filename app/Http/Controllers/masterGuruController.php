<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class masterGuruController extends Controller
{
    public function index(Request $request)
    {
        $kode_jenjang = Auth::guard('user')->user()->kode_jenjang;
        $user = User::find(Auth::guard('user')->user()->id);
        $query = Guru::query();
        $query->select('guru.*', 'nama_jenjang');
        $query->join('jenjang', 'guru.kode_jenjang', '=', 'jenjang.kode_jenjang');
        $query->orderBy('nip');

        if (!empty($request->nama_guru)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_guru . '%');
        }
        if (!empty($request->kode_jenjang)) {
            $query->where('guru.kode_jenjang', $request->kode_jenjang);
        }
        if ($user->hasRole('Admin')) {
            $query->where('guru.kode_jenjang', $kode_jenjang);
        }
        $guru = $query->paginate(20);

        $jenjang = DB::table('jenjang')->get();
        return view('master_guru.dataGuru', compact('guru', 'jenjang'));
    }
    public function addguru(Request $request)
    {
        $nip = $request->nip;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_jenjang = $request->kode_jenjang;
        $password = Hash::make('12345');

        $request->validate([
            'foto' => 'image|mimes:png,jpg,jpeg|max:500',
        ]);
        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }
        try {
            $data = [
                'nip' => $nip,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_jenjang' => $kode_jenjang,
                'foto' => $foto,
                'password' => $password,
            ];
            $simpan = DB::table('guru')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/guru/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return Redirect::back()->with(['error' => "Data dengan NIY " . $nip . " Sudah ada"]);
            }
            return Redirect::back()->with(['error' => 'Data Gagal Di Simpan']);
        }
    }
    public function editguru(Request $request)
    {
        $nip = $request->nip;
        $jenjang = DB::table('jenjang')->get();
        $jabatan = DB::table('statusguru')->get();
        $guru = DB::table('guru')->where('nip', $nip)->first();
        return view('master_guru.editguru', compact('jenjang', 'guru', 'jabatan'));
    }
    public function updateguru($nip, Request $request)
    {
        $nip2 = $request->nip2;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp2 = $request->no_hp2;
        $kode_jenjang = $request->kode_jenjang;
        $password = bcrypt($request->password);
        $old_foto = $request->old_foto;
        $request->validate([
            'foto' => 'image|mimes:jpg,jpeg|max:500',
        ]);
        if ($request->hasFile('foto')) {
            $foto = $nip2 . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }
        if (isset($request->password)) {
            $data = [
                'nip' => $nip2,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp2,
                'kode_jenjang' => $kode_jenjang,
                'foto' => $foto,
                'password' => $password,
            ];
        } else {
            $data = [
                'nip' => $nip2,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp2,
                'kode_jenjang' => $kode_jenjang,
                'foto' => $foto,
            ];
        }

        $update = DB::table('guru')->where('nip', $nip)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/guru/";
                $folderPathOld = "public/uploads/guru/" . $old_foto;
                Storage::delete($folderPathOld);
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function deleteguru($nip)
    {
        try {
            DB::table('guru')->where('nip', $nip)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Delete']);
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return Redirect::back()->with(['error' => "Data dengan NIY " . $nip . " Sudah ada pengajuan izin"]);
            }
            return Redirect::back()->with(['error' => 'Data Gagal Di Delete']);
        }
    }
}
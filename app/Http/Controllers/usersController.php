<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class usersController extends Controller
{
    public function index(Request $request)
    {
        $jenjang = DB::table('jenjang')->orderBy('kode_jenjang')->get();
        $level = DB::table('roles')->orderBy('id')->get();
        $query = User::query();
        $query->select('users.id', 'users.name', 'email', 'nama_jenjang', 'roles.name as role');
        $query->join('jenjang', 'users.kode_jenjang', '=', 'jenjang.kode_jenjang');
        $query->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id');
        $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');
        if (!empty($request)) {
            $query->where('users.email', 'like', '%' . $request->name . '%');
        }
        $users = $query->paginate(10);
        $users->appends(request()->all());
        return view('users.users', compact('users', 'jenjang', 'level'));
    }
    public function addusers(Request $request)
    {
        $nama_users = $request->nama_users;
        $email = $request->email;
        $password = bcrypt($request->password);
        $jenjang = $request->jenjang;
        $level = $request->level;

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $nama_users,
                'email' => $email,
                'password' => $password,
                'kode_jenjang' => $jenjang,
            ]);
            $user->assignRole($level);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            //throw $th;
            if ($e->getCode() == 23000) {
                return Redirect::back()->with(['error' => "Data dengan e-mail " . $email . " sudah ada"]);
            }
            DB::rollBack();
            return Redirect::back()->with(['error' => 'Data Gagal Disimpan']);
        }
    }
    public function editusers(Request $request)
    {
        $id_user = $request->id_user;
        $jenjang = DB::table('jenjang')->orderBy('kode_jenjang')->get();
        $level = DB::table('roles')->orderBy('id')->get();
        $user = DB::table('users')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->where('id', $id_user)->first();
        return view('users.edituser', compact('level', 'jenjang', 'user'));
    }
    public function updateusers(Request $request, $id_user)
    {
        $nama_users = $request->nama_users;
        $email = $request->email;
        $password = bcrypt($request->password);
        $jenjang = $request->jenjang;
        $level = $request->level;

        if (isset($request->password)) {
            $data = [
                'name' => $nama_users,
                'email' => $email,
                'kode_jenjang' => $jenjang,
                'password' => $password,
            ];
        } else {
            $data = [
                'name' => $nama_users,
                'email' => $email,
                'kode_jenjang' => $jenjang,

            ];
        }
        DB::beginTransaction();
        try {
            //update db user
            DB::table('users')->where('id', $id_user)->update($data);

            //update db level
            DB::table('model_has_roles')->where('model_id', $id_user)
                ->update([
                    'role_id' => $level,
                ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }
    public function deleteusers($id_user)
    {
        try {
            DB::table('users')->where('id', $id_user)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => 'Data Gagal Dihapus']);
        }
    }
}

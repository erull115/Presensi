<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\cutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\gantiController;
use App\Http\Controllers\historiAbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\konfigurasiController;
use App\Http\Controllers\laporanController;
use App\Http\Controllers\masterGuruController;
use App\Http\Controllers\monitoringController;
use App\Http\Controllers\sakitController;
use App\Http\Controllers\usersController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware(['guest:guru'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});
Route::middleware(['guest:user'])->group(function () {
    Route::get('/admin', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

//route guru
Route::middleware(['auth:guru'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //proses logout
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //absensi
    Route::get('/Absensi/create', [AbsensiController::class, 'create']);
    Route::get('/Absensi/pilihabsen', [AbsensiController::class, 'pilihabsen']);
    Route::get('/Absensi/status_guru', [AbsensiController::class, 'status_guru']);
    Route::post('/Absensi/store', [AbsensiController::class, 'store']);

    //edit profil
    Route::get('editprofil', [AbsensiController::class, 'editprofil']);
    Route::post('/absensi/{nip}/updateprofile', [AbsensiController::class, 'updateprofile']);

    //histori
    Route::get('/histori/histori', [historiAbsensiController::class, 'histori']);
    Route::post('/histori/gethistori', [historiAbsensiController::class, 'gethistori']);

    //izin
    Route::get('/izin/izin', [IzinController::class, 'izin']);
    Route::get('/izin/form_izin', [IzinController::class, 'izinabsen']);
    Route::post('/izin/buat_izin', [IzinController::class, 'buat_izin']);

    //sakit
    Route::get('/sakit/form_sakit', [sakitController::class, 'sakit']);
    Route::post('/sakit/buat_izin_sakit', [sakitController::class, 'buat_izin_sakit']);

    //cuti
    Route::get('/cuti/form_cuti', [cutiController::class, 'cuti']);
    Route::post('/cuti/buat_izin_cuti', [cutiController::class, 'buat_izin_cuti']);

    //ganti
    Route::get('/ganti/form_ganti', [gantiController::class, 'ganti']);
    Route::get('/ganti/{kode_izin}/{status}/pengajuan_ganti', [gantiController::class, 'pengajuan_ganti']);
    Route::post('/ganti/buat_ganti', [gantiController::class, 'buat_ganti']);
});

//route admin
Route::group(['middleware' => ['role:Super User|Admin|Kepsek|Wakur,user']], function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/admin/dasboard', [AdminController::class, 'dashboard']);

    //data master guru
    Route::get('/dataguru', [masterGuruController::class, 'index']);
    Route::post('/guru/addguru', [masterGuruController::class, 'addguru']);
    Route::post('/guru/editguru', [masterGuruController::class, 'editguru']);
    Route::post('/guru/{nip}/updateguru', [masterGuruController::class, 'updateguru']);
    Route::post('/guru/{nip}/deleteguru', [masterGuruController::class, 'deleteguru']);

    //data master users
    Route::get('/admin/users', [usersController::class, 'index']);
    Route::post('/admin/addusers', [usersController::class, 'addusers']);
    Route::post('/admin/editusers', [usersController::class, 'editusers']);
    Route::post('/admin/users/{id_user}/update', [usersController::class, 'updateusers']);
    Route::post('/admin/users/{id_user}/deleteusers', [usersController::class, 'deleteusers']);

    //monitoring data
    Route::get('/admin/monitoring', [monitoringController::class, 'monitoring']);
    Route::post('/admin/getabsensi', [monitoringController::class, 'getabsensi']);
    Route::post('/admin/koreksiabsensi', [monitoringController::class, 'koreksiabsensi']);
    Route::post('/admin/koreksikehadiran', [monitoringController::class, 'koreksikehadiran']);
    Route::post('/admin/{nip}/{tanggal}/deleteabsen', [monitoringController::class, 'deleteabsen']);

    //konfigurasi jam kerja
    Route::get('/admin/jadwal', [konfigurasiController::class, 'jadwal']);
    Route::get('/admin/{nip}/setjamkerja', [konfigurasiController::class, 'setjamkerja']);
    Route::post('/admin/jadwaljamkerja', [konfigurasiController::class, 'jadwaljamkerja']);
    Route::post('/admin/{nip}/{hari}/deletejadwal', [konfigurasiController::class, 'deletejadwal']);

    //konfigurasi lokasi
    Route::get('/admin/lokasi', [konfigurasiController::class, 'lokasikantor']);
    Route::post('/admin/updatelokasi', [konfigurasiController::class, 'updatelokasi']);

    //report
    Route::get('/admin/laporanabsensi', [laporanController::class, 'laporanabsensi']);
    Route::post('/admin/cetakabsensi', [laporanController::class, 'cetakabsensi']);

    //pengajuan izin
    Route::get('/admin/pengajuanizin', [IzinController::class, 'pengajuanizin']);
    Route::get('/admin/{kode_izin}/batalizin', [IzinController::class, 'batalizin']);
    Route::post('/admin/datapengajuanizin', [IzinController::class, 'datapengajuanizin']);

    //pengajuan cuti
    Route::get('/admin/pengajuancuti', [cutiController::class, 'pengajuancuti']);
    Route::post('/admin/datapengajuancuti', [cutiController::class, 'datapengajuancuti']);
    Route::get('/admin/{kode_izin}/batalcuti', [cutiController::class, 'batalcuti']);

    //pengajuan ganti jam
    Route::get('/admin/datapergantianjam', [gantiController::class, 'datapergantianjam']);
    Route::post('/admin/datapengajuanganti', [gantiController::class, 'datapengajuanganti']);
    Route::get('/admin/{kode_ganti}/batalganti', [gantiController::class, 'batalganti']);

    //pengajuan sakit
    Route::get('/admin/pengajuan_sakit', [sakitController::class, 'pengajuan_sakit']);
    Route::post('/admin/datapengajuansakit', [sakitController::class, 'datapengajuansakit']);
    Route::get('/admin/{kode_izin}/batalsakit', [sakitController::class, 'batalsakit']);
});

Route::get('/createrolepermission', function () {

    try {
        // $role = Role::create(['name' => 'Super User']);
        // $role = Role::create(['name' => 'Admin']);
        $role = Role::create(['name' => 'Wakur']);
        $role = Role::create(['name' => 'Kepsek']);
        // Permission::create(['name' => 'admin']);
        // Permission::create(['name' => 'guru']);
        Permission::create(['name' => 'Wakur']);
        Permission::create(['name' => 'Kepsek']);

        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});
Route::get('/create-user', function () {
    try {
        $password = Hash::make("12345");
        echo $password;
    } catch (\Throwable $th) {
        //throw $th;
    }
});

Route::get('/give-user-role', function () {
    try {
        $user = User::findorfail(1);
        // $user->assignRole('Super User');
        $user->assignRole('Kepsek');
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});

Route::get('/give-role-permission', function () {
    try {
        $role = Role::findorfail(3);
        // $role->givePermissionTo('admin');
        $role->givePermissionTo('guru');
        $role->givePermissionTo('Wakur');
        // $role->givePermissionTo('Kepsek');
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});
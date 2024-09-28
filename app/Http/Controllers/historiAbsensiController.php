<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class historiAbsensiController extends Controller
{
        public function histori (){
            $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni"
                            ,"Juli","Agustus","September","Oktober","November","Desember"];
            return view('histori.histori', compact('namabulan'));
        }

        public function  gethistori(Request $request){
            $bulan = $request -> bulan;
            $tahun = $request -> tahun;

            $nip    = Auth::guard('guru')->user()->nip;

            $histori = DB::table('absensi')
            ->where('nip',$nip)
            ->whereRaw('MONTH(tgl_absensi)="'. $bulan . '"')
            ->whereRaw('YEAR(tgl_absensi)="'. $tahun .'"')
            ->orderBy('tgl_absensi','desc')
            ->orderBy('jam_in', 'desc')
            ->get();

            return view('histori.gethistori', compact('histori'));
    }
}

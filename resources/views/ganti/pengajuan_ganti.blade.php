@extends('layouts.absensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        #keterangan {
            height: 5rem;
        }
    </style>
    <div class="appHeader bg-warning text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Pergantian Jam Mengajar</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/ganti/buat_ganti" id="frmganti">
                @csrf
                @foreach ($data_pengajuan as $i)
                    @if ($status == 'i')
                        <div class="form-group">
                            <input type="text" class="form-control" name="tanggal1" id="tanggal1"
                                value="{{ date('Y-m-d') }}" disabled>
                        </div>
                        <div class="form-group">
                            <input type="hidden" value="{{ $i->kode_izin }}" id="kode_izin" name="kode_izin">
                            <input type="hidden" value="{{ $i->status }}" id="status" name="status">
                            <input type="text" class="form-control" value="{{ $i->nama_lengkap }}" id="ganti"
                                name="ganti" readonly>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" value="{{ $i->jml_jp }}" id="jml_jp"
                                name="jml_jp" readonly>
                            <input type="text" class="form-control" value="{{ $i->jml_jp }} Jam Pelajaran" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="Izin Absen" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="ket" id="ket"
                                value="{{ $i->keterangan }}" readonly>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary w-100">Submit</button>
                        </div>
                    @endif
                @endforeach
                @foreach ($sakit as $s)
                    @if ($status == 's')
                        <div class="form-group">
                            <input type="text" class="form-control" name="tanggal1" id="tanggal1"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                        <div class="form-group">
                            <input type="hidden" value="{{ $s->kode_izin }}" id="kode_izin" name="kode_izin">
                            <input type="hidden" value="{{ $s->status }}" id="status" name="status">
                            <input type="text" class="form-control" value="{{ $s->nama_lengkap }}" id="ganti"
                                name="ganti" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="Izin Sakit" readonly>
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" value="{{ $s->jp }}" id="jml_jp"
                                name="jml_jp" readonly>
                            <input type="text" class="form-control" value="{{ $s->jp }} Jam Pelajaran" readonly>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="ket" id="ket"
                                value="{{ $s->keterangan }}" readonly>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary w-100">Submit</button>
                        </div>
                    @endif
                @endforeach

            </form>
        </div>
    </div>
@endsection

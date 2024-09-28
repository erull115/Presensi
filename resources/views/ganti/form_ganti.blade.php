@extends('layouts.absensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pengajuan Mengganti Jam</div>
        <div class="right"></div>
    </div>
    <style>
        .jamganti {
            display: flex;
        }

        .datajamganti {
            margin-left: 10px;
            text-align: left;
        }

        .status {
            position: absolute;
            margin-top: 0.2rem;
            right: 25px;
        }
    </style>
@endsection
@section('content')
    <div class="col-12" style="padding: 5px; margin-top: 65px;">
        {{-- messege --}}
        @php
            $pesansuccess = Session::get('success');
            $pesanerror = Session::get('error');
            $pesanwarning = Session::get('warning');
        @endphp
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ $pesansuccess }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ $pesanerror }}
            </div>
        @endif
        @if (Session::get('warning'))
            <div class="alert alert-warning">
                {{ $pesanwarning }}
            </div>
        @endif
        {{-- messege --}}
        @foreach ($datagantijam as $d)
            <a href="/ganti/{{ Crypt::encrypt($d->kode_izin) }}/{{ $d->status }}/pengajuan_ganti" style="color: black">
                @if ($nip != $d->nip)
                    @if ($cekizin == 0)
                        <div class="card w-100 mb-1">
                            <div class="card-body">
                                <div class="jamganti">
                                    <div class="iconcontent">
                                        <ion-icon name="document-outline" style="font-size: 48px; color: #f5e508"></ion-icon>
                                    </div>
                                    <div class="datajamganti">
                                        <h4 style="margin: 0px !important">
                                            {{ $d->nama_lengkap }} ({{ $d->kode_jenjang }}) - Izin</h4>
                                        <span>{{ date('H:i', strtotime($d->jam1)) }} - </span>
                                        <span>{{ date('H:i', strtotime($d->jam2)) }}</span>
                                        <br>
                                        <span>{{ $d->keterangan }}</span>
                                    </div>
                                    <div class="status" style="text-align: right">
                                        <small>{{ $d->jml_jp }} JP</small>
                                        <br>
                                        @if ($d->status_approved == 1)
                                            <span style="font-size: 25px; color:green">
                                                <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </a>
        @endforeach
        @foreach ($sakit as $s)
            @php
                $hari1 = hitunghari($s->tanggal1, $tanggal);
                $hari2 = $s->jml_hari;
                if ($tanggal < $s->tanggal1) {
                    $sisa = -1;
                } elseif ($tanggal > $s->tanggal2) {
                    $sisa = 0;
                } else {
                    $sisa = $hari2 - $hari1;
                }
            @endphp

            @if ($sisa >= 0)
                @if ($tanggal <= $s->tanggal2)
                    @if ($nip != $s->nip)
                        <a href="/ganti/{{ Crypt::encrypt($s->kode_izin) }}/{{ $s->status }}/pengajuan_ganti"
                            style="color: black">
                            <div class="card w-100 mb-1">
                                <div class="card-body">
                                    <div class="jamganti">
                                        <div class="iconcontent">
                                            <ion-icon name="medkit-outline"
                                                style="font-size: 48px; color: #f50808"></ion-icon>
                                        </div>
                                        <div class="datajamganti">
                                            <h4 style="margin: 0px !important">
                                                {{ $s->nama_lengkap }} ({{ $s->kode_jenjang }})</h4>
                                            <span style="font-size: 12px;">{{ date('d-m-Y', strtotime($s->tanggal1)) }} s/d
                                                {{ date('d-m-Y', strtotime($s->tanggal2)) }}</span><br>
                                            <span>Izin {{ hitunghari($s->tanggal1, $s->tanggal2) }} Hari</span>
                                            <br>
                                            <span>{{ $s->keterangan }}</span>
                                        </div>
                                        <div class="status" style="text-align: right">
                                            <small>{{ $s->jp }} JP</small>
                                            <br>
                                            @if ($s->status_approved == 1)
                                                <span style="font-size: 25px; color:green">
                                                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                @endif
            @endif
        @endforeach
    </div>
@endsection

@extends('layouts.absensi')
@section('header')
    <div class="appHeader bg-success text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Histori Pengajuan</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    {{-- messege --}}
    <div class="row" style="margin-top: 70px">
        <div class="col">
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
        </div>
    </div>
    {{-- messege --}}

    {{-- item histori --}}
    <style>
        .historicontent {
            display: flex;
        }

        .datahistoriizin {
            margin-left: 15px;
        }

        .status {
            position: absolute;
            margin-top: 0.2rem;
            right: 25px;
        }

        .status2 {
            position: absolute;
            margin-top: 4rem;
            right: 25px;
        }
    </style>
    <div class="row">
        <div class="col">
            {{-- histori izin absen --}}
            @foreach ($datahistoribuatizin as $i)
                @php
                    if ($i->status == 'i') {
                        $status = 'Izin Absen';
                    }
                @endphp
                <div class="col" style="padding: 5px">
                    <div class="card">
                        <div class="card-body" style="padding: 12px 12px !important; line-height: 1.2rem">
                            <div class="historicontent">
                                <div class="iconcontent">
                                    @if ($i->status == 'i')
                                        <ion-icon name="document-outline"
                                            style="font-size: 48px; color: #f5e508"></ion-icon>
                                    @endif
                                </div>

                                <div class="datahistoriizin">
                                    <h3 style="margin-bottom: 0px !important; font-size: 20px"><b>{{ $status }}
                                        </b></h3>
                                    <small
                                        style="font-size: 12px; font-weight: bold">{{ date('d-m-Y', strtotime($i->tanggal1)) }}</small><br>

                                    <small>{{ date('H:m', strtotime($i->jam1)) }} s/d
                                        {{ date('H:m', strtotime($i->jam2)) }}</small>
                                    <br>
                                    <span>{{ $i->keterangan }}</span>
                                </div>

                                <div class="status" style="text-align: right">
                                    @if ($i->status_approved == 0)
                                        <span style="font-size: 30px; color:orange">
                                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                                        </span>
                                    @elseif ($i->status_approved == 1)
                                        <span style="font-size: 30px; color:green">
                                            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                                        </span>
                                    @elseif ($i->status_approved == 2)
                                        <span style="font-size: 30px; color:red">
                                            <ion-icon name="close-circle-outline"></ion-icon>
                                        </span>
                                    @endif
                                    <br>
                                    <small style="font-weight: bold">{{ $i->jml_jp }} JP</small><br>
                                    <small>{{ $i->kode_izin }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- histori izin sakit --}}
            @foreach ($datahistoribuatsakit as $a)
                @php
                    if ($a->status == 's') {
                        $status = 'Izin Sakit';
                    }
                @endphp
                <div class="col" style="padding: 5px">
                    <div class="card">
                        <div class="card-body" style="padding: 12px 12px !important; line-height: 1.2rem">
                            <div class="historicontent">
                                <div class="iconcontent">
                                    @if ($a->status == 's')
                                        <ion-icon name="medkit-outline" style="font-size: 48px; color: #f50808"></ion-icon>
                                    @endif
                                </div>

                                <div class="datahistoriizin">
                                    <h3 style="margin-bottom: 0px !important; font-size: 20px"><b>{{ $status }}</b>
                                    </h3>
                                    <small style="font-size: 12px; color: #111111"><b>{{ date('d-m-y', strtotime($a->tanggal1)) }}
                                            s/d
                                            {{ date('d-m-y', strtotime($a->tanggal2)) }}</b></small><br>
                                    @if (!empty($a->doc_sid))
                                        <small style="color: #0818f5; font-size: 12px">
                                            <ion-icon name="document-attach-outline"></ion-icon> SID
                                        </small><br>
                                    @endif
                                    <span>{{ $a->keterangan }} </span>


                                </div>

                                <div class="status" style="text-align: right">
                                    @if ($a->status_approved == 0)
                                        <span style="font-size: 30px; color:orange">
                                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                                        </span>
                                    @elseif ($a->status_approved == 1)
                                        <span style="font-size: 30px; color:green">
                                            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                                        </span>
                                    @elseif ($a->status_approved == 2)
                                        <span style="font-size: 30px; color:red">
                                            <ion-icon name="close-circle-outline"></ion-icon>
                                        </span>
                                    @endif
                                    <br>
                                    <small style="font-weight: bold">
                                        @php
                                            $hari1 = hitunghari($a->tanggal1, $tanggal);
                                            $hari2 = $a->jml_hari;
                                            $sisa = $hari2 - $hari1;
                                        @endphp
                                        @if ($tanggal < $a->tanggal1)
                                            Sisa {{ $hari2 - 0 }} Hari
                                        @elseif($tanggal > $a->tanggal2)
                                            Sisa 0 Hari
                                        @else
                                            Sisa {{ $sisa + 1 }} Hari
                                        @endif

                                    </small><br>
                                    <small>{{ $a->kode_izin }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- histori izin sakit --}}

            {{-- histori buat cuti --}}
            @foreach ($datahistoribuatcuti as $d)
                @php
                    if ($d->status == 'c') {
                        $status = 'Izin Cuti';
                    }
                @endphp
                <div class="col" style="padding: 5px">
                    <div class="card">
                        <div class="card-body" style="padding: 12px 12px !important; line-height: 1.2rem">
                            <div class="historicontent">
                                <div class="iconcontent">
                                    @if ($d->status == 'c')
                                        <ion-icon name="calendar-number-outline"
                                            style="font-size: 48px; color: #08f586"></ion-icon>
                                    @endif
                                </div>
                                <div class="datahistoriizin">
                                    <h3 style="margin-bottom: 0px !important; font-size: 20px"><b>{{ $status }}</b>
                                    </h3>
                                    <small style="font-size: 12px; color: #111111"><b>
                                            {{ date('d-m-y', strtotime($d->tanggal1)) }}
                                            s/d {{ date('d-m-y', strtotime($d->tanggal2)) }}</b>
                                    </small><br>
                                    <small>
                                        @php
                                            $hari1 = hitunghari($d->tanggal1, $tanggal);
                                            $hari2 = $d->jml_hari;
                                            $sisa = $hari2 - $hari1;
                                        @endphp
                                        @if ($tanggal < $d->tanggal1)
                                            Sisa {{ $hari2 - 0 }} Hari
                                        @elseif($tanggal > $d->tanggal2)
                                            Sisa 0 Hari
                                        @else
                                            Sisa {{ $sisa + 1 }} Hari
                                        @endif
                                    </small><br>
                                    <small>
                                        @if ($d->status = 'c')
                                            <span style="color: #08f586"><b>{{ $d->keterangan }}</b></span>
                                        @endif
                                    </small>
                                </div>
                                <div class="status" style="text-align: right">
                                    @if ($d->status_approved == 0)
                                        <span style="font-size: 30px; color:orange">
                                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                                        </span>
                                    @elseif ($d->status_approved == 1)
                                        <span style="font-size: 30px; color:green">
                                            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                                        </span>
                                    @elseif ($d->status_approved == 2)
                                        <span style="font-size: 30px; color:red">
                                            <ion-icon name="close-circle-outline"></ion-icon>
                                        </span>
                                    @endif
                                    <br>
                                    <small style="margin-top: 5px; font-weight: bold">
                                        {{ hitunghari($d->tanggal1, $d->tanggal2) }} Hari</small><br>
                                    <small>{{ $d->kode_izin }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- item histori cuti --}}
        </div>
    </div>


    {{-- buttom melayang --}}
    <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
        <a href="#" class="fab bg-primary" data-toggle="dropdown">
            <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item bg-primary" href="/izin/form_izin">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                <p>Absen</p>
            </a>
            <a class="dropdown-item bg-primary" href="/sakit/form_sakit">
                <ion-icon name="document-outline" role="img" class="md hydrated"
                    aria-label="vidiocam outline"></ion-icon>
                <p>Sakit</p>
            </a>
            <a class="dropdown-item bg-primary" href="/cuti/form_cuti">
                <ion-icon name="document-outline" role="img" class="md hydrated"
                    aria-label="vidiocam outline"></ion-icon>
                <p>Cuti</p>
            </a>
        </div>
    </div>
    {{-- buttom melayang --}}
@endsection

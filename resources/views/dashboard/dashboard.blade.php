@extends('layouts.absensi')

@section('content')
    <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 30px;
            right: 30px;
            top: 40px;
        }

        .logout:hover {
            color: white;
        }
    </style>

    <div class="section" id="user-section">

        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('guru')->user()->foto))
                    @php
                        $path = Storage::url('uploads/guru/' . Auth::guard('guru')->user()->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height: 60px">
                @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                @endif
            </div>
            <div id="user-info">
                <h3 id="user-name">{{ Auth::guard('guru')->user()->nama_lengkap }}</h3>
                <span id="user-role">{{ Auth::guard('guru')->user()->nip }}</span>
                <a href="/proseslogout" class="logout">
                    <ion-icon name="power"></ion-icon>
                </a>
            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofil" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/ganti/form_ganti" class="danger" style="font-size: 40px;">
                                <ion-icon name="time"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Change</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="histori/histori" class="warning" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Histori</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="orange" style="font-size: 40px;">
                                <ion-icon name="location"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Lokasi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <ion-icon name="camera"></ion-icon>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span">{{ $absensiHariIni != null ? $absensiHariIni->jam_in : 'Belum absen' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <ion-icon name="camera"></ion-icon>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $absensiHariIni != null && $absensiHariIni->jam_out != null ? $absensiHariIni->jam_out : 'Belum absen' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id = "rekapabsensi">
            <h3> Rekap Absensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.5rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size: 0.6rem;
                    z-index:999">{{ $rekapabsensi->jmlhadir }}</span>
                            <ion-icon name="people-outline" style="font-size: 1.7rem" class="text-success mb-1"></ion-icon>
                            <span style="font-size: 0.9rem; font-weight:500">Hadir</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.5rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size: 0.6rem;
                    z-index:999">{{ $rekapizin->jmlizin }}</span>
                            <ion-icon name="newspaper-outline" style="font-size: 1.7rem"
                                class="text-primary mb-1"></ion-icon>
                            <span style="font-size: 0.9rem; font-weight:500 mt-2">Izin </span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.5rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size: 0.6rem;
                    z-index:999">{{ $rekapsakit->jmlsakit }}</span>
                            <ion-icon name="medkit-outline" style="font-size: 1.7rem"
                                class="text-warning mb-1"></ion-icon>
                            <span style="font-size: 0.9rem; font-weight:500">Sakit</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 12px !important; line-height:0.5rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size: 0.6rem;
                    z-index:999">{{ $rekapcuti->jmlcuti }}</span>
                            <ion-icon name="calendar-outline" style="font-size: 1.7rem"
                                class="text-danger mb-1"></ion-icon>
                            <span style="font-size: 0.9rem; font-weight:500">Cuti</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="presencetab mt-2">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <ul class="nav nav-tabs style1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                            Bulan Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                            Leaderboard
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content mt-2" style="margin-bottom:100px;">
                <div class="tab-pane fade show active" id="home" role="tabpanel">

                    <style>
                        .historicontent {
                            display: flex;
                        }

                        .dataabsensi {
                            margin-left: 10px;
                        }

                        .status {
                            position: absolute;
                            margin-top: 0.2rem;
                            text-align: right;
                            right: 25px;
                        }
                    </style>
                    @foreach ($historibulanini as $d)
                        @if ($d->jam_in != null)
                            <div class="col" style="padding: 5px">
                                <div class="card">
                                    <div class="card-body" style="padding: 12px 12px !important; line-height: 1.2rem">
                                        <div class="historicontent">
                                            <div class="iconcontent">
                                                <ion-icon name="finger-print-outline" style="font-size: 48px"
                                                    class="text-success"></ion-icon>
                                            </div>
                                            <div class="dataabsensi">
                                                <h3 style="margin: 0px !important">
                                                    {{ date('d-m-Y', strtotime($d->tgl_absensi)) }} ({{ $d->jml_jp }}
                                                    JP)
                                                </h3>
                                                <span>
                                                    {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span style="color: red">Belum absen</span>' !!}
                                                    s/d
                                                </span>
                                                <span>
                                                    {!! $d->jam_out != null ? date('H:i', strtotime($d->jam_out)) : '<span style="color: red">Belum absen</span>' !!}
                                                </span>
                                                <br>
                                                {!! $d->jam_out != null ? '<span style="color: green">Selesai</span>' : '<span style="color: red">Belum</span>' !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    {{-- item histori ganti --}}
                    @foreach ($datahistoribuatganti as $m)
                        @php
                            if ($m->status == 'm') {
                                $status = 'Menggantikan';
                            }
                        @endphp
                        <div class="col" style="padding: 5px">
                            <div class="card">
                                <div class="card-body" style="padding: 12px 12px !important; line-height: 1.2rem">
                                    <div class="historicontent">
                                        <div class="iconcontent">
                                            @if ($m->status == 'm')
                                                <ion-icon name="people-outline"
                                                    style="font-size: 48px; color: #ece811"></ion-icon>
                                            @endif
                                        </div>
                                        <div class="dataabsensi">
                                            <h3 style="margin-bottom: 0px !important">
                                                <b>{{ date('d-m-Y', strtotime($m->tanggal)) }} ({{ $m->tot_jam }}
                                                    JP)</b>
                                            </h3>
                                            <span>{{ $m->kode_ganti }}</span><br>
                                            <span style="font-size: 15"><b>{{ $status }}</b></span>

                                        </div>
                                        <div class="status" style="text-align: right">
                                            @if ($m->status_approved == 0)
                                                <span style="font-size: 30px; color:orange">
                                                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                                                </span>
                                            @elseif ($m->status_approved == 1)
                                                <span style="font-size: 30px; color:green">
                                                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                                                </span>
                                            @elseif ($m->status_approved == 2)
                                                <span style="font-size: 30px; color:red">
                                                    <ion-icon name="close-circle-outline"></ion-icon>
                                                </span>
                                            @endif
                                            <br><span style="color: blcak; font-size: 15px"><b>
                                                    {{ $m->ganti }}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <ul class="listview image-listview">
                        @foreach ($leaderboard as $d)
                            @if ($d->jam_in != null)
                                <li>
                                    <div class="item">
                                        <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                        <div class="in">
                                            <div>
                                                <b>{{ $d->nama_lengkap }}</b><br>
                                                <small>{{ $d->jam_in }}</small>
                                                <small>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge badge-danger">Belum</span>' !!}</small>
                                                <br>
                                                <small class="text-muted">{{ $d->kode_jenjang }}</small>
                                            </div>
                                            <div>
                                                <span>
                                                    {!! $d->jam_out != null
                                                        ? '<span class="badge badge-success">Selesai</span>'
                                                        : '<span class="badge badge-danger">Belum</span>' !!}
                                                </span>
                                            </div>
                                        </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

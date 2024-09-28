@extends ('layouts.absensi')
@section('content')
    <!-- App Header -->
    <div class="appHeader bg-success text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pilih Jam</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .jadwal {
            display: flex;
            color: black;
        }

        .datajadwal {
            margin-left: 10px;
        }

        .status {
            position: absolute;
            margin-top: 0.2rem;
            right: 25px;
        }
    </style>
    @if ($jadwalhariini != null)
        @foreach ($jadwalhariini as $d)
            <div class="row" style="padding-top: 65px">
                @if ($d->jp1_in != null)
                    <div class="col-12" style="padding: 5px">
                        <a href="/Absensi/create">
                            <div class="card">
                                <div class="card-body">
                                    <div class="jadwal">
                                        <div class="iconcontent">
                                            <ion-icon name="finger-print-outline" style="font-size: 48px"
                                                class="text-success"></ion-icon>
                                        </div>
                                        <div class="datajadwal">
                                            <h3 style="margin: 0px !important">
                                                {{ $d->hari }}</h3>
                                            <span>{{ date('H:i', strtotime($d->jp1_in)) }} - </span>
                                            <span>{{ date('H:i', strtotime($d->jp1_out)) }}</span>
                                        </div>
                                        <div class="status">
                                            <span>{{ $d->jp }} Jam pelajaran</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
@endsection

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
    <div class="row" style="margin-top: 65px">
        <div class="col">
            <div class="alert alert-warning">
                Maat tidak ada jadwal hari ini, silahkan hubungi admin !
            </div>
        </div>
    </div>
@endsection

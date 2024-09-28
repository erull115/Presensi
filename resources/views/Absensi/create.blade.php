@extends('layouts.absensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-success text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Login</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 260px;
        }

        .jam-digital-malasngoding {

            background-color: #27272783;
            position: absolute;
            top: 65px;
            right: 15px;
            z-index: 9999;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
        }

        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: center;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="{{ asset('assets/js/lib/leaflet-map.js') }}"></script>
@endsection

@section('content')
    <div class="raw" style="margin-top: 65px">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>

        </div>
    </div>
    <div class="jam-digital-malasngoding">
        <p>{{ date('d-m-Y') }}</p>
        <p id="jam"></p>
        <p>Mulai : {{ date('H:i', strtotime($jammapel->jp1_in)) }}</p>
        <p>Selesai : {{ date('H:i', strtotime($jammapel->jp1_out)) }}</p>
    </div>

    <!-- * tombol absen -->
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <button id="ambilfoto" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @else
                <button id="ambilfoto" class="btn btn-success btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif
        </div>
    </div>


    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <audio id="notifikasi_in">
        <source src="{{ asset('assets/audio/notifikasi_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_out">
        <source src="{{ asset('assets/audio/notifikasi_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_radius">
        <source src="{{ asset('assets/audio/radius.mp3') }}" type="audio/mpeg">
    </audio>
@endsection

@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>
    <script>
        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');
        var notifikasi_radius = document.getElementById('notifikasi_radius');
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture')
        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        //menampilakan map
        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 25);
            var lokasi_kantor = "{{ $lokasi_kantor->lokasi }}";
            var radius_kantor = "{{ $lokasi_kantor->radius }}";
            var lok = lokasi_kantor.split(",");
            var lat_lokasi = lok[0];
            var long_lokasi = lok[1];
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            //position.coords.latitude, position.coords.longitude
            //markerlokasisekolah
            var circle = L.circle([lat_lokasi, long_lokasi], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius_kantor
            }).addTo(map);

            marker.bindPopup("<b>Lokasi Saya</b>").openPopup();
            circle.bindPopup("<b>Yayasan Generasi Muslim Cendekia</b>");

        }

        function errorCallback() {

        }

        //menyimpan data
        $('#ambilfoto').click(function(e) {

            //kamera
            Webcam.snap(function(uri) {
                image = uri;
            });

            //lokasi
            var lokasi = $('#lokasi').val();
            $.ajax({
                type: 'POST',
                url: '/Absensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|");
                    if (status[0] == "success") {
                        if (status[2] == "in") {
                            notifikasi_in.play();
                        } else {
                            notifikasi_out.play();
                        }
                        Swal.fire({
                            title: 'Berhasil',
                            text: status[1],
                            icon: 'Succes'
                        });
                        setTimeout("location.href='/dashboard'", 3000)
                    } else {
                        if (status[2] == "radius") {
                            notifikasi_radius.play();
                        }
                        Swal.fire({
                            title: 'Error',
                            text: status[1],
                            icon: 'Error'
                        });
                        setTimeout("location.href='/dashboard'", 3000)
                    }
                }
            });
        });
    </script>
@endpush

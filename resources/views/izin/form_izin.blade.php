@extends('layouts.absensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <style>
        .timepicker-digital-display {
            padding: 25px !important;
        }

        .timepicker-modal {
            max-height: 420px !important;
        }

        .timepicker-display-am-pm {
            bottom: 0.1rem !important;
        }

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
        <div class="pageTitle">Form Izin Absen</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/izin/buat_izin" id="frmizin">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Tanggal" autocomplete="off" name="tanggal1"
                        id="tanggal1" value="{{ date('Y-m-d') }}" disabled>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Dari Jam" autocomplete="off" name="jam1"
                        id="jam1">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Sampai Jam" autocomplete="off" name="jam2"
                        id="jam2">
                </div>
                <div class="form-group">
                    <select name="jp" id="jp" class="form-control selectmaterialize">
                        <option value="">Jumlah Jam Pelajaran</option>
                        <option value="1">1 Jam Pelajaran</option>
                        <option value="2">2 Jam Pelajaran</option>
                        <option value="3">3 Jam Pelajaran</option>
                        <option value="4">4 Jam Pelajaran</option>
                        <option value="5">5 Jam Pelajaran</option>
                        <option value="6">6 Jam Pelajaran</option>
                        <option value="7">7 Jam Pelajaran</option>
                        <option value="8">8 Jam Pelajaran</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Keterangan" autocomplete="off" name="keterangan"
                        id="keterangan">
                </div>

                <div class="form-group">
                    <button class="btn btn-primary w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(document).ready(function() {
            $('#jam1').timepicker();
            $('#jam2').timepicker();
        });

        $("#frmizin").submit(function() {
            var jam1 = $("#jam1").val();
            var jam2 = $("#jam2").val();
            var jp = $("#jp").val();
            var keterangan = $("#keterangan").val();
            if (jam1 == "") {
                Swal.fire({
                    tittle: 'Maaf !',
                    text: 'Dari Jam harus diisi !',
                    icon: 'warning'
                });
                return false;
            } else if (jam2 == "") {
                Swal.fire({
                    tittle: 'Maaf !',
                    text: 'Sampai Jam harus diisi !',
                    icon: 'warning'
                });
                return false;
            } else if (jp == "") {
                Swal.fire({
                    tittle: 'Maaf !',
                    text: 'Jumlah Jam harus diisi !',
                    icon: 'warning'
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    tittle: 'Maaf !',
                    text: 'Keterangan harus diisi !',
                    icon: 'warning'
                });
                return false;
            }
        });
    </script>
@endpush

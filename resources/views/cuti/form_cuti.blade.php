@extends('layouts.absensi')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        .datepicker-modal {
            max-height: 430px !important;
        }

        .datepicker-date-display {
            background-color: #1e74fd !important;
        }

        #keterangan {
            height: 5rem;
        }
    </style>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin Cuti</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form method="POST" action="/cuti/buat_izin_cuti" id="frmizin">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control datepicker" placeholder="Dari Tanggal" autocomplete="off"
                        name="tanggal1" id="tanggal1">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control datepicker" placeholder="Sampai Tanggal" autocomplete="off"
                        name="tanggal2" id="tanggal2">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" placeholder="Jumlah hari" autocomplete="off" name="jml_hari"
                        id="jml_hari" readonly>
                    <input type="text" class="form-control" placeholder="Jumlah hari" autocomplete="off" name="jml_hari2"
                        id="jml_hari2" readonly>
                </div>
                <div class="form-group">
                    <select name="kode_cuti" id="kode_cuti" class="form-control selectmaterialize">
                        <option value="">Keterangan Cuti</option>
                        @foreach ($mastercuti as $c)
                            <option value="{{ $c->kode_cuti }}">{{ $c->nama_cuti }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" class="form-control" placeholder="Keterangan" autocomplete="off" name="keterangan"
                    id="keterangan" readonly>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });
            //napilkan keterangan
            function tampil() {
                var tampil_ket = $("#kode_cuti").val();
                if (tampil_ket == "C01") {
                    $('#keterangan').val("Cuti Melahirkan");
                } else if (tampil_ket == "C02") {
                    $('#keterangan').val("Cuti Menikah");
                } else {
                    $('#keterangan').val(" ");
                }
            }

            $('#kode_cuti').change(function(e) {
                tampil();
            });


            //menghitunghari
            function loadjumlahhari() {
                var dari = $("#tanggal1").val();
                var sampai = $("#tanggal2").val();
                var date1 = new Date(dari);
                var date2 = new Date(sampai);

                var Difference_In_Time = date2.getTime() - date1.getTime();
                var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

                var jmlhari = Difference_In_Days + 1;

                if (Difference_In_Days < -1) {
                    var jmlhari = 0;
                } else {
                    $("#jml_hari").val(jmlhari);
                    $("#jml_hari2").val(jmlhari + " Hari");
                }
            }
            $("#tanggal1" && "#tanggal2").change(function(e) {
                loadjumlahhari();
            });

            $("#frmizin").submit(function() {
                var tanggal1 = $("#tanggal1").val();
                var tanggal2 = $("#tanggal2").val();
                var keterangan = $("#keterangan").val();

                if (tanggal1 == "") {
                    Swal.fire({
                        tittle: 'Maaf !',
                        text: 'Dari Tanggal harus diisi !',
                        icon: 'warning'
                    });
                    return false;
                } else if (tanggal2 == "") {
                    Swal.fire({
                        tittle: 'Maaf !',
                        text: 'Sampai Tanggal harus diisi !',
                        icon: 'warning'
                    });
                    return false;
                } else if (kode_cuti == "") {
                    Swal.fire({
                        tittle: 'Maaf !',
                        text: 'Keterangan Cuti harus diisi !',
                        icon: 'warning'
                    });
                    return false;
                }
            });
        });
    </script>
@endpush

@extends('layouts.admin.tabler')
@section('content')
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
    </style>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Setting Jam Pelajaran
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="row" style="margin-top: 70px">
                        <div class="col">
                            @php
                                $pesansuccess = Session::get('success');
                                $pesanerror = Session::get('error');
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
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>NIY</th>
                            <td>{{ $guru->nip }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $guru->nama_lengkap }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <form action="/admin/jadwaljamkerja" id="frmjadwal" method="POST">
                        @csrf
                        <input type="hidden" name="nip" id="nip" value="{{ $guru->nip }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jumlah Jam</th>
                                    <th>Masuk</th>
                                    <th>Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 25%; padding-right: 10px">
                                        <div class="form-group">
                                            <select name="hari" id="hari" class="form-select">
                                                <option value="Senin">Senin</option>
                                                <option value="Selasa">Selasa</option>
                                                <option value="Rabu">Rabu</option>
                                                <option value="Kamis">Kamis</option>
                                                <option value="Jumat">Jumat</option>
                                                <option value="Sabtu">Sabtu</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td style="width: 35%;padding-right: 10px">
                                        <div class="form-group">
                                            <select name="jp" id="jp" class="form-select">
                                                <option value="">Pilih Jam Pelajaran</option>
                                                <option value="1">1 Jam Pelajaran</option>
                                                <option value="2">2 Jam Pelajaran</option>
                                                <option value="3">3 Jam Pelajaran</option>
                                                <option value="4">4 Jam Pelajaran</option>
                                                <option value="5">5 Jam Pelajaran</option>
                                                <option value="6">6 Jam Pelajaran</option>
                                                <option value="7">7 Jam Pelajaran</option>
                                                <option value="8">8 Jam Pelajaran</option>
                                                <option value="9">9 Jam Pelajaran</option>
                                                <option value="10">10 Jam Pelajaran</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td style="padding-right: 10px">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="--:--"
                                                autocomplete="off" name="jam1_in" id="jam1_in"
                                                style="font-size: 12px; text-align:center">
                                        </div>
                                    </td>
                                    <td style="padding-right: 10px">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="--:--"
                                                autocomplete="off" name="jam1_out" id="jam1_out"
                                                style="font-size: 12px;text-align:center">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>HARI</th>
                                <th>Jam pelajaran</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwal as $d)
                                <tr style="font-size: 10px">
                                    <td>{{ $d->hari }}</td>
                                    <td>{{ $d->jp }} Jam Pelajaran</td>
                                    <td>{{ $d->jp1_in }}</td>
                                    <td>{{ $d->jp1_out }}</td>
                                    <td>
                                        <form action="/admin/{{ $d->nip }}/{{ $d->hari }}/deletejadwal"
                                            method="POST" style="margin-left: 5px">
                                            @csrf
                                            <button class="btn btn-danger btn-sm delete-confirm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire({
                        title: "Deleted!",
                        text: "Data sudah dihapus",
                        icon: "success"
                    });
                }
            });
        });
        $("#frmjadwal").submit(function() {
            var jp = $('#jp').val();
            var jp1_in = $('#jam1_in').val();
            var jp1_out = $('#jam1_out').val();
            if (jp == "") {
                // alert("NIY Harus Diisi !");
                Swal.fire({
                    title: 'Warning!',
                    text: 'JP Harus Diisi !',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#jp").focus();
                });
                return false;
            } else if (jp1_in == "") {
                // alert("NIY Harus Diisi !");
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jam Masuk Harus Diisi !',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#jp").focus();
                });
                return false;
            } else if (jp1_out == "") {
                // alert("NIY Harus Diisi !");
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jam Pulang Harus Diisi !',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#jp").focus();
                });
                return false;
            }
        });
        $(function() {
            $("#jam1_in").mask('00:00');
            $("#jam1_out").mask('00:00');
        });
    </script>
@endpush

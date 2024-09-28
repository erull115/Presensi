@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Monitoring
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
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
                                        @error('foto')
                                            <div class="alert alert-warning">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-icon mb-3">
                                            <span class="input-icon-addon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                                    <path d="M16 3l0 4" />
                                                    <path d="M8 3l0 4" />
                                                    <path d="M4 11l16 0" />
                                                    <path d="M8 15h2v2h-2z" />
                                                </svg>
                                            </span>
                                            <input type="text" id="tanggal" value="{{ date('Y-m-d') }}" name="tanggal"
                                                value="" class="form-control" placeholder="Tanggal Absensi"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table-striped table-hover table">
                                            <thead>
                                                <tr>
                                                    <th>NIY</th>
                                                    <th>Nama</th>
                                                    <th>Jenjang</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Foto</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Foto</th>
                                                    <th>Jumlah Jam</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="loadabsensi">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal edit --}}
    <div class="modal modal-blur fade" id="modal-koreksiabsen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Koreksi Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadkoreksiabsensi">

                </div>
            </div>
        </div>
    </div>
    {{-- modal edit --}}
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#tanggal").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

            function loadabsensi() {
                var tanggal = $("#tanggal").val();
                $.ajax({
                    type: 'POST',
                    url: '/admin/getabsensi',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function(respond) {
                        $('#loadabsensi').html(respond);
                    }
                });
            }
            $("#tanggal").change(function(e) {
                loadabsensi();
            });
            loadabsensi();
        });
    </script>
@endpush

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
                        Data Pengajuan Sakit
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="col-12">
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
                        <div class="row mb-2">
                            <div class="col-6">
                                <form action="/admin/pengajuancuti" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="kode_izin" id="kode_izin" class="form-control"
                                                    placeholder="Kode Izin" value="{{ Request('kode_izin') }}"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                        <path d="M21 21l-6 -6" />
                                                    </svg>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <table class="table-bordered table">
                                            <thead>
                                                <tr>
                                                    <th>Kode Izin</th>
                                                    <th>NIY</th>
                                                    <th>Nama</th>
                                                    <th>Tanggal Awal</th>
                                                    <th>Tanggal Akhir</th>
                                                    <th>Jenjang</th>
                                                    <th style="width: 250px">Keterangan</th>
                                                    <th>Jumlah Hari</th>
                                                    <th><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-paperclip">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" />
                                                        </svg></th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 10px">
                                                @foreach ($sakit as $d)
                                                    <tr>
                                                        <td>{{ $d->kode_izin }}</td>
                                                        <td>{{ $d->nip }}</td>
                                                        <td>{{ $d->nama_lengkap }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($d->tanggal1)) }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($d->tanggal2)) }}</td>
                                                        <td>{{ $d->kode_jenjang }}</td>
                                                        <td>{{ $d->keterangan }}</td>
                                                        <td>{{ hitunghari($d->tanggal1, $d->tanggal2) }} Hari
                                                        </td>
                                                        <td>
                                                            @if (!empty($d->doc_sid))
                                                                @php
                                                                    $path = Storage::url('uploads/sid/' . $d->doc_sid);
                                                                @endphp
                                                                <a href="{{ url($path) }}" target="_blank">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-paperclip">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9l6.5 -6.5" />
                                                                    </svg>
                                                                </a>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if ($d->status_approved == 1)
                                                                <span class="badge bg-success">Disetujui</span>
                                                            @elseif($d->status_approved == 2)
                                                                <span class="badge bg-danger">Ditolak</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($d->status_approved == 0)
                                                                <a href="#" class="approve btn btn-sm btn-primary"
                                                                    kd_cuti="{{ $d->kode_izin }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-pencil-check">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                                                        <path d="M13.5 6.5l4 4" />
                                                                        <path d="M15 19l2 2l4 -4" />
                                                                    </svg>
                                                                </a>
                                                            @else
                                                                <a href="/admin/{{ $d->kode_izin }}/batalsakit"
                                                                    class="btn btn-sm btn-danger">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-square-x">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" />
                                                                        <path d="M9 9l6 6m0 -6l-6 6" />
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $sakit->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-blur fade" id="modal-pengajuansakit" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/admin/datapengajuansakit" method="POST">
                            @csrf
                            <input type="hidden" id="id_sakit_form" name="id_sakit_form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="status_approved" id="status_approved" class="form-select">
                                            <option value="1">Disetujui</option>
                                            <option value="2">Ditolak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary w-100" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 14l11 -11" />
                                                <path
                                                    d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                            </svg>
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function() {
            $('.approve').click(function(e) {
                e.preventDefault();
                var kd_cuti = $(this).attr("kd_cuti");
                $('#id_sakit_form').val(kd_cuti);
                $('#modal-pengajuansakit').modal("show");
            });
        });
    </script>
@endpush

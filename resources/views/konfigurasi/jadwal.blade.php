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
                        Jadwal Pelajaran
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <style>
        .table-bordered {
            font-size: 12px;
        }
    </style>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12">
                            <form action="/admin/jadwal" method="GET">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <input type="text" name="nama_guru" id="nama_guru" class="form-control"
                                                placeholder="Nama Guru" value="{{ Request('nama_guru') }}">
                                        </div>
                                    </div>
                                    @role('Super User', 'user')
                                        <div class="col-4">
                                            <div class="from-group">
                                                <select name="kode_jenjang" id="kode_jenjang" class="form-select">
                                                    <option value="">Jenjang</option>
                                                    @foreach ($jenjang as $d)
                                                        <option
                                                            {{ Request('kode_jenjang') == $d->kode_jenjang ? 'selected' : '' }}
                                                            value="{{ $d->kode_jenjang }}">
                                                            {{ $d->nama_jenjang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endrole
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
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Jenjang</th>
                                                <th>Hari</th>
                                                <th>Jumlah Jam</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                @role('Super User', 'user')
                                                    <th>Aksi</th>
                                                @endrole
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jadwal as $d)
                                                <tr>
                                                    <td>{{ $loop->iteration + $jadwal->firstItem() - 1 }}</td>
                                                    <td>{{ $d->nama_lengkap }}</td>
                                                    <td>{{ $d->kode_jenjang }}</td>
                                                    <td>{{ $d->hari }}</td>
                                                    <td>{{ $d->jp }} Jam Pelajaran</td>
                                                    <td>{{ $d->jp1_in }}</td>
                                                    <td>{{ $d->jp1_out }}</td>
                                                    <td>
                                                        <form
                                                            action="/admin/{{ $d->nip }}/{{ $d->hari }}/deletejadwal"
                                                            method="POST" style="margin-left: 5px">
                                                            @csrf
                                                            <button class="btn btn-danger btn-sm delete-confirm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 7l16 0" />
                                                                    <path d="M10 11l0 6" />
                                                                    <path d="M14 11l0 6" />
                                                                    <path
                                                                        d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $jadwal->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
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
    </script>
@endpush

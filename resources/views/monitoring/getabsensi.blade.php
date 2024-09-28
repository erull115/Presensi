{{--  --}}
@foreach ($absensi as $d)
    @if ($d->jam_in != null)
        @php
            $foto_in = Storage::url('uploads/presensi/' . $d->foto_in);
            $foto_out = Storage::url('uploads/presensi/' . $d->foto_out);
        @endphp
        <tr>
            <td>{{ $d->nip }}</td>
            <td>{{ $d->nama_lengkap }}</td>
            <td>{{ $d->kode_jenjang }}</td>
            <td>{{ $d->jam_in }}</td>
            <td>
                @if ($d->foto_in != null)
                    <img src="{{ url($foto_in) }}" class="avatar" alt="">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-photo-cancel">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 8h.01" />
                        <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3 3" />
                        <path d="M14 14l1 -1c.616 -.593 1.328 -.792 2.008 -.598" />
                        <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M17 21l4 -4" />
                    </svg>
                @endif
            </td>
            <td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge bg-danger">Belum absen</span>' !!}</td>
            <td>
                @if ($d->foto_out != null)
                    <img src="{{ url($foto_out) }}" class="avatar" alt="">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-photo-cancel">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 8h.01" />
                        <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                        <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3 3" />
                        <path d="M14 14l1 -1c.616 -.593 1.328 -.792 2.008 -.598" />
                        <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M17 21l4 -4" />
                    </svg>
                @endif
            </td>
            <td>{{ $d->jml_jp }} Jam Pelajaran</td>
            <td>
                {!! $d->jam_out != null
                    ? '<span class="badge bg-success">Selesai</span>'
                    : '<span class="badge bg-danger">Belum</span>' !!}
            </td>
            <td>
                <div class="d-flex">
                    <div class="form-group">
                        <a href="#" class="btn btn-sm btn-primary btn-sm koreksiabsensi"
                            nip="{{ $d->nip }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                        </a>
                    </div>
                    <div class="form-group" style="padding-left: 2px">
                        @role('Super User|Kepsek|Wakur', 'user')
                            <form action="/admin/{{ $d->nip }}/{{ $tanggal }}/deleteabsen" method="POST">
                                @csrf
                                <a class="btn btn-danger btn-sm delete-confirm ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </a>
                            </form>
                        @endrole
                    </div>
                </div>
            </td>
        </tr>
    @endif
@endforeach
<script>
    $(".koreksiabsensi").click(function() {
        var nip = $(this).attr('nip');
        var tanggal = "{{ $tanggal }}";

        $.ajax({
            type: 'POST',
            url: '/admin/koreksiabsensi',
            cache: false,
            data: {
                _token: "{{ csrf_token() }}",
                nip: nip,
                tanggal: tanggal
            },
            success: function(respond) {
                $("#loadkoreksiabsensi").html(respond);
            }
        });
        $("#modal-koreksiabsen").modal("show");
    });
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

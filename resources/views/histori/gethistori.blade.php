@if($histori->isEmpty())
<div class="alert alert-outline-warning">
    <p>Data Belum Ada</p>
</div>
@endif

@foreach ($histori as $d )
    <ul class="listview image-listview">
        <li>
            <div class="item">
                @php
                    $path = Storage::url('uploads/presensi/'.$d->foto_in);
                @endphp
                <img src="{{ url($path) }}" alt="image" class="image">
                {{-- <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image"> --}}
                <div class="in">
                    <div>
                        <b>{{ date("d-m-y",strtotime($d->tgl_absensi)) }}</b><br>
                        {{--<small class="text-muted">{{ $d -> jabatan }}</small>--}}
                    </div>
                    <div>
                        <span class="badge {{ $d->jam_in < "08:00" ? "bg-success" : "bg-danger" }}">
                            {{ $d -> jam_in }}
                        </span>
                        <span class="badge bg-primary">{{ $d != null && $d->jam_out != null ? $d
                                -> jam_out :  'Belum absen' }}</span>
                    </div>
            </div>
        </li>
    </ul>
@endforeach

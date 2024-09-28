<form action="/guru/{{ $guru->nip }}/updateguru" method="POST" id="frmguru" enctype="multipart/form-data">
    @csrf
    <div class="col-12">
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-scan">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 9a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                    <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                    <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                    <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                    <path d="M8 16a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2" />
                </svg>
            </span>
            <input type="text" value="{{ $guru->nip }}" id="nip2" name="nip2" class="form-control"
                placeholder="Nomer Induk Yayasan">
        </div>
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                </svg>
            </span>
            <input type="text" value="{{ $guru->nama_lengkap }}" class="form-control" id="nama_lengkap"
                name="nama_lengkap" placeholder="Nama Lengkap">
        </div>
        <div class="input-icon mb-3">
            <select name="jabatan" id="jabatan" class="form-select">
                @foreach ($jabatan as $d)
                    <option {{ $guru->jabatan == $d->nama_status ? 'selected' : '' }} value="{{ $d->nama_status }}">
                        {{ $d->nama_status }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
                <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-phone">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                </svg>
            </span>
            <input type="text" value="{{ $guru->no_hp }}" class="form-control" id="no_hp2" name="no_hp2"
                placeholder="Nomer HP" autocomplete="off">
        </div>
        <div class="input-icon mb-3">
            <span class="input-icon-addon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-lock-password">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" />
                    <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                    <path d="M15 16h.01" />
                    <path d="M12.01 16h.01" />
                    <path d="M9.02 16h.01" />
                </svg>
            </span>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
        </div>

        <div class="mb-3">
            <input type="file" name="foto" id="foto" class="form-control">
            <input type="hidden" name="old_foto" value="{{ $guru->foto }}">
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <select name="kode_jenjang" id="kode_jenjang" class="form-select">
                    <option value="">
                        Jenjang
                    </option>
                    @foreach ($jenjang as $d)
                        <option {{ $guru->kode_jenjang == $d->kode_jenjang ? 'selected' : '' }}
                            value="{{ $d->kode_jenjang }}">
                            {{ $d->nama_jenjang }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-reload">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19.933 13.041a8 8 0 1 1 -9.925 -8.788c3.899 -1 7.935 1.007 9.425 4.747" />
                            <path d="M20 4v5h-5" />
                        </svg>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $("#no_hp2").mask('000000000000');
        $("#nip2").mask('00000');
    });
</script>

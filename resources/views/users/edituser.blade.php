<form action="/admin/users/{{ $user->id }}/update" method="POST" id="frmusers">
    @csrf
    <div class="col-12">
        <div class="input-icon mb-2">
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
            <input type="text" value="{{ $user->name }}" id="nama_users" name="nama_users" class="form-control"
                placeholder="Nama User">
        </div>
        <div class="input-icon mb-2">
            <span class="input-icon-addon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-mail">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                    <path d="M3 7l9 6l9 -6" />
                </svg>
            </span>
            <input type="text" value="{{ $user->email }}" class="form-control" id="email" name="email"
                placeholder="E-mail">
        </div>
        <div class="input-icon mb-2">
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
            <input type="password" value="" class="form-control" id="password" name="password"
                placeholder="Password">
        </div>
        <div class="input-icon mb-2">
            <select name="jenjang" id="jenjang" class="form-select">
                <option value="">Pilih Jenjang</option>
                @foreach ($jenjang as $d)
                    <option {{ $user->kode_jenjang == $d->kode_jenjang ? 'selected' : '' }}
                        value="{{ $d->kode_jenjang }}">{{ $d->nama_jenjang }}</option>
                @endforeach
            </select>
        </div>
        <div class="input-icon mb-3">
            <select name="level" id="level" class="form-select">
                <option value="">Pilih Level</option>
                @foreach ($level as $d)
                    <option {{ $user->role_id == $d->id ? 'selected' : '' }} value="{{ $d->id }}">
                        {{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 14l11 -11" />
                            <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                        </svg>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

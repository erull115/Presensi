<form action="/admin/koreksikehadiran" method="POST" id="koreksiabsensi">
    @csrf
    <input type="hidden" id="nip" name="nip" value="{{ $guru->nip }}">
    <input type="hidden" id="tanggal" name="tanggal" value="{{ $tanggal }}">
    <table class="table">
        <tr>
            <td>NIY</td>
            <td>:</td>
            <td>{{ $guru->nip }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $guru->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Tanggal Absensi</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime($tanggal)) }}</td>
        </tr>
    </table>
    <div class="row mb-3">
        <div class="col-12">
            <select name="jp" id="jp" class="form-select mb-1">
                <option value="">Jumlah Jam Pelajaran</option>
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
            <div class="input-icon mb-1">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                </span>
                <input type="text" id="jam_out" name="jam_out" class="form-control" placeholder="Jam Selesai"
                    autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $('#jam_in,#jam_out').mask('00:00')
    });
    $(function() {
        $("#koreksiabsensi").submit(function() {
            var jp = $("#jp").val();
            var jam_out = $("#jam_out").val();
            if (jp == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jumlah Jam Harus Diisi !',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#jp").focus();
                });
                return false;
            } else if (jam_out == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Jam Harus Diisi !',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#jp").focus();
                });
                return false;
            }

        })
    });
</script>

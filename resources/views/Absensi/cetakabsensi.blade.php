<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: bold;
        }

        .tabledataguru {
            margin-top: 40px;
        }

        .tabledataguru tr td {
            padding: 5px;
        }

        .tableabsensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tableabsensi tr th {
            border: 1px solid #0c0c0c;
            padding: 8px;
            background-color: #8efa84;
        }

        .tableabsensi tr td {
            border: 1px solid #0c0c0c;
            padding: 5px;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 90px">
                    <img src="{{ asset('assets/img/login/logogmc.jpg') }}" width="80" height="80" alt="">
                </td>
                <td>
                    <span id="title">
                        LAPORAN ABSENSI GURU <br>
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} <br>
                        YAYASAN GENERASI MUSLIM CENDEKIA <br>
                    </span>
                    <span><i>Jln. Sanggar Belajar Puyung</i></span>
                </td>
            </tr>
        </table>
        <table class="tabledataguru">
            <tr>
                <td rowspan="4">
                    @php
                        $path = Storage::url('uploads/guru/' . $guru->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="90px" height="100px"
                        style="margin-right: 8px">
                </td>
            </tr>
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
                <td>Jenjang</td>
                <td>:</td>
                <td>{{ $guru->nama_jenjang }} ({{ $guru->kode_jenjang }})</td>
            </tr>
        </table>
        <table class="tableabsensi">
            <tr>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Foto</th>
                <th>Jam Selesai</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Jam Pelajaran</th>
            </tr>

            @foreach ($absensi as $d)
                @if ($d->jam_in != null)
                    <tr>
                        @php
                            $path_in = Storage::url('uploads/presensi/' . $d->foto_in);
                            $path_out = Storage::url('uploads/presensi/' . $d->foto_out);
                        @endphp
                        <td>{{ date('d-m-Y', strtotime($d->tgl_absensi)) }}</td>
                        <td>{{ $d->jam_in }}</td>
                        <td><img src="{{ url($path_in) }}" alt=""width="30px" height="30px"></td>
                        <td>{{ $d->jam_out != null ? $d->jam_out : 'Tidak Absen' }}</td>
                        <td><img src="{{ url($path_out) }}" alt=""width="30px" height="30px"></td>
                        <td>
                            @if ($d->jam_out != null)
                                <span class="badge bg-success">Hadir</span>
                            @else
                                <span class="badge bg-danger">Belum Absen</span>
                            @endif
                        </td>
                        <td></td>
                        <td>{{ $d->jml_jp }} JP</td>
                    </tr>
                @endif
            @endforeach
            @foreach ($ganti as $g)
                @if ($g->status_approved == 1)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($g->tanggal)) }}</td>
                        <td>{{ $g->jam1 }}</td>
                        <td></td>
                        <td>{{ $g->jam2 }}</td>
                        <td></td>
                        <td>
                            @if ($g->status_approved == 1)
                                <span class="badge bg-success">Menggantikan</span>
                            @else
                                <span class="badge bg-danger"></span>
                            @endif
                        </td>
                        <td>{{ $g->ganti }}</td>
                        <td>{{ $g->tot_jam }} JP</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="7"><b> Total Absensi </b></td>
                <td>
                    @if ($totabsensi->Total_absen == null)
                        0 JP
                    @else
                        {{ $totabsensi->Total_absen }} JP
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="7"><b>Total Menggantikan</b></td>
                <td>
                    @if ($totganti->Total == null)
                        0 JP
                    @else
                        {{ $totganti->Total }} JP
                    @endif
                </td>
            </tr>
            {{-- <tr>
                <td colspan="7"><b>Total Izin</b></td>
                <td>
                    @if ($totizin->Total_izin == null)
                        0 JP
                    @else
                        {{ $totizin->Total_izin }} JP
                    @endif
                </td>
            </tr> --}}
            <tr>
                <td colspan="7"><b>Total</b></td>
                <td>{{ $total }} JP</td>
            </tr>

        </table>
        <table width="100%" style="margin-top: 50px">
            <tr>
                <td colspan="2" style="text-align: right; padding-right:120px">Puyung, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align:buttom" height="200px">
                    <u>{{ Auth::guard('user')->user()->name }}</u><br>
                    <i>admin</i>
                </td>
                <td style="text-align: center; vertical-align:buttom" height="100px">
                    <u>Nama Ketua Yas</u><br>
                    <i>Ketua Yas</i>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>

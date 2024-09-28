@extends('layouts.absensi')
@section('header')
    <div class="appHeader bg-success text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Profil</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <form action="/absensi/{{ $edituser->nip }}/updateprofile" method="POST" enctype="multipart/form-data"
        style="margin-top: 4rem">
        <div class="col-12" style="margin-top: 4rem">
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
        @csrf
        <div class="col">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control selectmaterialize" value="{{ $edituser->nama_lengkap }}"
                        name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control selectmaterialize" maxlength="12"
                        value="{{ $edituser->no_hp }}" name="no_hp" id="no_hp" placeholder="No. HP"
                        autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control selectmaterialize" name="password" placeholder="Password"
                        autocomplete="off">
                </div>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload</i>
                        </strong>
                    </span>
                    <input type="hidden" name="old_foto" value="{{ $edituser->foto }}">
                </label>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#no_hp").mask('000000000000');
        });
    </script>
@endpush

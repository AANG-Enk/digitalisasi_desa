@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    Update Profile - Digitalisasi Desa
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card mb-6">
    <!-- Account -->
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-6">
            <img
            src="{{ (!is_null(auth()->user()->foto)) ? asset('storage/'.auth()->user()->foto) : asset('assets/img/logo/logo.png') }}"
            alt="Foto Profile {{ auth()->user()->name }}"
            class="d-block w-px-100 h-px-100 rounded-4"
            id="uploadedAvatar" />
            <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Upload new photo</span>
                    <i class="ri-upload-2-line d-block d-sm-none"></i>
                    <input
                    type="file"
                    id="upload"
                    class="account-file-input"
                    hidden
                    accept="image/png, image/jpeg" />
                </label>
                <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <span class="text-danger">* Wajib diisi</span>
        <form method="post" action="{{ $action }}">
            @csrf
            <div class="row mt-1 g-5">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control @error('nik') is-invalid @enderror" type="number" name="nik" id="nik" value="{{ $user->nik }}" />
                        <label for="nik">No. NIK <span class="text-danger">*</span></label>
                    </div>
                    @error('nik')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control @error('kk') is-invalid @enderror" type="number" name="kk" id="kk" value="{{ $user->kk }}" />
                        <label for="kk">No. KK <span class="text-danger">*</span></label>
                    </div>
                    @error('kk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                        class="form-control @error('name') is-invalid @enderror"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ $user->name }}"
                        autofocus />
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                        class="form-control @error('username') is-invalid @enderror"
                        type="text"
                        id="username"
                        name="username"
                        value="{{ $user->username }}"
                        autofocus />
                        <label for="username">Username <span class="text-danger">*</span></label>
                    </div>
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                    <input
                        class="form-control @error('email') is-invalid @enderror"
                        type="text"
                        id="email"
                        name="email"
                        value="{{ $user->email }}"/>
                        <label for="email">E-mail <span class="text-danger">*</span></label>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control @error('tempat_lahir') is-invalid @enderror" type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $user->tempat_lahir }}" />
                        <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                    </div>
                    @error('tempat_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control @error('tanggal_lahir') is-invalid @enderror" type="text" name="tanggal_lahir" id="tanggal_lahir" value="{{ \Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('DD-MM-YYYY') }}" />
                        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                    </div>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                  type="radio"
                                  name="jenis_kelamin"
                                  id="laki-laki"
                                  {{ (isset($user) && $user->jenis_kelamin == 'L') ? 'checked' : '' }}
                                  value="L" />
                                <label class="form-check-label" for="laki-laki">Laki-Laki</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                  type="radio"
                                  name="jenis_kelamin"
                                  id="perempuan"
                                  {{ (isset($user) && $user->jenis_kelamin == 'P') ? 'checked' : '' }}
                                  value="P" />
                                <label class="form-check-label" for="perempuan">Perempuan</label>
                            </div>
                        </div>
                        @error('tanggal_lahir')
                            <div class="col-sm-12">
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                        class="form-control @error('rt') is-invalid @enderror"
                        type="number"
                        id="rt"
                        name="rt"
                        value="{{ $user->rt }}"/>
                        <label for="rt">RT <span class="text-danger">*</span></label>
                    </div>
                    @error('rt')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                        class="form-control @error('rw') is-invalid @enderror"
                        type="number"
                        id="rw"
                        name="rw"
                        value="{{ $user->rw }}"/>
                        <label for="rw">RW <span class="text-danger">*</span></label>
                    </div>
                    @error('rw')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <div class="form-floating form-floating-outline">
                        <textarea name="alamat" id="alamat_rumah" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat',$user->alamat) }}</textarea>
                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                    </div>
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                            class="form-control @error('hp') is-invalid @enderror"
                            type="number"
                            id="hp"
                            name="hp"
                            value="{{ $user->hp }}"/>
                        <label for="hp">No. Whatsapp <span class="text-danger">*</span></label>
                    </div>
                    <span class="text-secondary">Contoh : 62896050161</span>
                    @error('hp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input
                            class="form-control @error('jenis_pekerjaan') is-invalid @enderror"
                            type="text"
                            id="jenis_pekerjaan"
                            name="jenis_pekerjaan"
                            value="{{ $user->jenis_pekerjaan }}" />
                        <label for="jenis_pekerjaan">Jenis Pekerjaan <span class="text-danger">*</span></label>
                    </div>
                    @error('jenis_pekerjaan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <label class="col-sm-12">Status Pernikahan <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status_pernikahan') is-invalid @enderror"
                                  type="radio"
                                  name="status_pernikahan"
                                  id="sudah"
                                  {{ (isset($user) && $user->status_pernikahan == 'S') ? 'checked' : '' }}
                                  value="S" />
                                <label class="form-check-label" for="sudah">Sudah</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status_pernikahan') is-invalid @enderror"
                                  type="radio"
                                  name="status_pernikahan"
                                  id="belum"
                                  {{ (isset($user) && $user->status_pernikahan == 'B') ? 'checked' : '' }}
                                  value="B" />
                                <label class="form-check-label" for="belum">Belum</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status_pernikahan') is-invalid @enderror"
                                  type="radio"
                                  name="status_pernikahan"
                                  id="pernah"
                                  {{ (isset($user) && $user->status_pernikahan == 'P') ? 'checked' : '' }}
                                  value="P" />
                                <label class="form-check-label" for="pernah">Pernah</label>
                            </div>
                        </div>
                        @error('status_pernikahan')
                            <div class="col-sm-12">
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Simpan</button>
            </div>
        </form>
    </div>
    <!-- /Account -->
</div>
@endsection

@section('vendorjs')
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){
            var datePicker = $('#tanggal_lahir')
            if (datePicker.length) {
                datePicker.datepicker({
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                orientation: isRtl ? 'auto right' : 'auto left'
                });
            }
        })
    </script>
@endsection

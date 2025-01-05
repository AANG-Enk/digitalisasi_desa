@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    @if (isset($user))
        Edit Warga {{ $user->name }} - Digitalisasi Desa
    @else
        Tambah Warga - Digitalisasi Desa
    @endif
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('datawarga.index') }}">Data Warga</a>
            </li>
            @if (isset($user))
                <li class="breadcrumb-item active">Edit Warga {{ $user->name }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Warga</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Tambah Role</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @isset($user) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="kk">No. KK <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="number"
                                class="form-control @error('kk') is-invalid @enderror"
                                id="kk"
                                name="kk"
                                value="{{ isset($user) ? old('kk',$user->kk) : old('kk') }}"
                                placeholder="Masukkan No. KK" />
                            @error('kk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="nik">No. NIK <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="number"
                                class="form-control @error('nik') is-invalid @enderror"
                                id="nik"
                                name="nik"
                                value="{{ isset($user) ? old('nik',$user->nik) : old('nik') }}"
                                placeholder="Masukkan No. NIK" />
                            @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="name">Nama <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ isset($user) ? old('name',$user->name) : old('name') }}"
                                placeholder="Masukkan Nama" />
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="tempat_lahir">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                id="tempat_lahir"
                                name="tempat_lahir"
                                value="{{ isset($user) ? old('tempat_lahir',$user->tempat_lahir) : old('tempat_lahir') }}"
                                placeholder="Masukkan Tempat Lahir" />
                            @error('tempat_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                id="tanggal_lahir"
                                name="tanggal_lahir"
                                value="{{ isset($user) ? old('tanggal_lahir',\Carbon\Carbon::parse($user->tanggal_lahir)->isoFormat('DD-MM-YYYY')) : old('tanggal_lahir') }}"
                                placeholder="Masukkan Tempat Lahir" />
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" >Jenis Kelamin</label>
                        <div class="col-sm-10">
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
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" >Alamat</label>
                        <div class="col-sm-10 row">
                            <div class="col-sm-6">
                                <label class="col-form-label" for="rt">RT</label>
                                <input
                                    type="number"
                                    class="form-control @error('rt') is-invalid @enderror"
                                    id="rt"
                                    name="rt"
                                    value="{{ isset($user) ? old('rt',$user->rt) : old('rt') }}"
                                    placeholder="Masukkan RT" />
                                @error('rt')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="col-form-label" for="rw">RW</label>
                                <input
                                    type="number"
                                    class="form-control @error('rw') is-invalid @enderror"
                                    id="rw"
                                    name="rw"
                                    value="{{ isset($user) ? old('rw',$user->rw) : old('rw') }}"
                                    placeholder="Masukkan RW" />
                                @error('rw')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-sm-12">
                                <label class="col-form-label" for="alamat_rumah">Alamat Rumah</label>
                                <textarea name="alamat" id="alamat_rumah" class="form-control">{{ isset($user) ? old('alamat',$user->alamat) : old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" >Status Pernikahan</label>
                        <div class="col-sm-10">
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
                            @error('status_pernikahan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="no_hp">No. HP/Whatsapp</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('no_hp') is-invalid @enderror"
                                id="no_hp"
                                name="hp"
                                value="{{ isset($user) ? old('hp',$user->hp) : old('hp') }}"
                                placeholder="Masukkan No. HP/Whatsapp" />
                            @error('hp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" >Status</label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="data-warga-tetap"
                                  {{ (isset($user) && $user->status == 'TETAP') ? 'checked' : '' }}
                                  value="Tetap" />
                                <label class="form-check-label" for="data-warga-tetap">Tetap</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="data-warga-pindah"
                                  {{ (isset($user) && $user->status == 'PINDAH') ? 'checked' : '' }}
                                  value="Pindah" />
                                <label class="form-check-label" for="data-warga-pindah">Pindah</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="data-warga-non-tetap"
                                  {{ (isset($user) && $user->status == 'NON TETAP') ? 'checked' : '' }}
                                  value="Non Tetap" />
                                <label class="form-check-label" for="data-warga-non-tetap">Non Tetap</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="data-warga-tidak-diketahui"
                                  {{ (isset($user) && $user->status == 'TIDAK DIKETAHUI') ? 'checked' : '' }}
                                  value="Tidak Diketahui" />
                                <label class="form-check-label" for="data-warga-tidak-diketahui">Tidak Diketahui</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('status') is-invalid @enderror"
                                  type="radio"
                                  name="status"
                                  id="data-warga-meninggal"
                                  {{ (isset($user) && $user->status == 'MENINGGAL') ? 'checked' : '' }}
                                  value="Meninggal" />
                                <label class="form-check-label" for="data-warga-meninggal">Meninggal</label>
                            </div>
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="jenis_pekerjaan">Jenis Pekerjaan</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('jenis_pekerjaan') is-invalid @enderror"
                                id="jenis_pekerjaan"
                                name="jenis_pekerjaan"
                                value="{{ isset($user) ? old('jenis_pekerjaan',$user->jenis_pekerjaan) : old('jenis_pekerjaan') }}"
                                placeholder="Masukkan Tempat Lahir" />
                            @error('jenis_pekerjaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

@extends('layouts.backend.main')

@section('title')
    Ganti Kata Sandi - Digitalisasi Desa
@endsection

@section('content')
@if(session('status') == 'password-updated')
    <div class="alert alert-success">
        Password Anda Berhasil Dirubah
    </div>
@endif

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Ganti Kata Sandi</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @method('PUT')
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="current_password">Kata Sandi Sekarang <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="password"
                                class="form-control  @error('current_password', 'updatePassword') is-invalid @enderror"
                                id="current_password"
                                name="current_password"
                                placeholder="Masukkan Kata Sandi Sekarang" />
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="password">Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="password"
                                class="form-control  @error('password', 'updatePassword') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Masukkan Kata Sandi Baru" />
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="password_confirmation">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="password"
                                class="form-control  @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Masukkan Kata Sandi Kembali" />
                            @error('password_confirmation', 'updatePassword')
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

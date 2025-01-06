@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('title')
    Surat {{ $layanansurat->pembuat->name }} - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('layanansurat.index') }}">List Permission</a>
            </li>
            <li class="breadcrumb-item active">Surat {{ $layanansurat->pembuat->name }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Tambah Nomor Registrasi {{ ($flag == 'rt')?'RT':'RW' }}</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="nomor">Nomor Nomor Registrasi {{ ($flag == 'rt')?'RT':'RW' }}<span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('nomor') is-invalid @enderror"
                                id="nomor"
                                name="nomor"
                                value="{{ isset($nomor) ? old('nomor',($flag == 'rt')?$layanansurat->nomor_rt:$layanansurat->nomor_rw) : old('nomor') }}"
                                placeholder="Masukkan Nama Permission" />
                            @error('nomor')
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

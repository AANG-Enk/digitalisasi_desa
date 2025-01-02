@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('title')
    @if (isset($permission))
        Edit Permission - Digitalisasi Desa
    @else
        Tambah Permission - Digitalisasi Desa
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
                <a href="{{ route('permissions.index') }}">List Permission</a>
            </li>
            @if (isset($permission))
                <li class="breadcrumb-item active">Edit Permission {{ $permission->name }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Permission</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Tambah Permission</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @isset($permission) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="name">Nama <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ isset($permission) ? old('name',$permission->name) : old('name') }}"
                                placeholder="Masukkan Nama Permission" />
                            @error('name')
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

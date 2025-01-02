@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('title')
    @if (isset($role))
        Edit Role - Digitalisasi Desa
    @else
        Tambah Role - Digitalisasi Desa
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
                <a href="{{ route('roles.index') }}">List Role</a>
            </li>
            @if (isset($role))
                <li class="breadcrumb-item active">Edit Role {{ $role->name }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Role</li>
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
                    @isset($role) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="name">Nama <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ isset($role) ? old('name',$role->name) : old('name') }}"
                                placeholder="Masukkan Nama Role" />
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

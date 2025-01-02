@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('title')
    @if (isset($user))
        Edit Pengguna - Digitalisasi Desa
    @else
        Tambah Pengguna - Digitalisasi Desa
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
                <a href="{{ route('users.index') }}">Users Management</a>
            </li>
            @if (isset($user))
                <li class="breadcrumb-item active">Edit Pengguna {{ $user->name }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Pengguna</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Tambah Pengguna</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @isset($user) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="username">Username <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                id="username"
                                name="username"
                                value="{{ isset($user) ? old('username',$user->username) : old('username') }}"
                                placeholder="Masukkan Username" />
                            @error('username')
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
                        <label class="col-sm-2 col-form-label" for="email">Email <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email Address"
                                aria-label="Email Address"
                                value="{{ isset($user) ? old('email',$user->email) : old('email') }}"
                                aria-describedby="email" />
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="role">Role <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select
                              id="select2Basic"
                              name="role"
                              class="select2 form-select form-select-lg"
                              data-allow-clear="true">
                                <option value="">Pilih Roles</option>
                                @foreach ($roles as $item)
                                    <option
                                        value="{{ $item->name }}"
                                        {{ (isset($user) && $user->hasRole($item->name)) ? 'selected' : ((!is_null(old('role')) && old('role') == $item->name) ? 'selected' : '') }}
                                        >{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="password">Password @if(!isset($user)) <span class="text-danger">*</span> @endif</label>
                        <div class="col-sm-10">
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Password" />
                            @error('password')
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
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

<script>
    $(function () {
        const select2 = $('.select2');
        if (select2.length) {
            select2.each(function () {
                var $this = $(this);
                select2Focus($this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Roles',
                    dropdownParent: $this.parent()
                });
            });
        }
    })
</script>
@endsection

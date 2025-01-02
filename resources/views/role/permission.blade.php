@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('title')
    Sinkronisasi Permission Role {{ $roles->name }} - Digitalisasi Desa
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
            <li class="breadcrumb-item active">Sinkronisasi Permission Role {{ $roles->name }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Sinkronisasi Permission</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="name">Permission</label>
                        <div class="col-sm-10">
                            <div class="row">
                                @foreach ($permissions as $item)
                                    <div class="col-md">
                                        <div class="form-check custom-option custom-option-basic">
                                            <label class="form-check-label custom-option-content" for="permission-{{ $item->name }}">
                                                <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $item->id }}" id="permission-{{ $item->name }}" {{ ($roles->hasPermissionTo($item->name))?'checked':'' }} />
                                                <span class="custom-option-header">
                                                    <span class="h6 mb-0">{{ $item->name }}</span>
                                                    <small class="text-muted">{{ $item->guar_name }}</small>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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

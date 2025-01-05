@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('title')
    @if (isset($surveipertanyaan))
        Edit Pertanyaan {{ $surveipertanyaan->judul }} - Digitalisasi Desa
    @else
        Tambah Pertanyaan - Digitalisasi Desa
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
                <a href="{{ route('surveirw.index') }}">Survei RW</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('surveirw.pertanyaan.index',$surveirw->id) }}">{{ $surveirw->judul }}</a>
              </li>
            @if (isset($surveipertanyaan))
                <li class="breadcrumb-item active">Edit Pertanyaan {{ $surveipertanyaan->judul }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Pertanyaan</li>
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
                <form method="POST" action="{{ $action }}" id="form-area">
                    @isset($surveipertanyaan) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="pertanyaan">Pertanyaan <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('pertanyaan') is-invalid @enderror"
                                id="pertanyaan"
                                name="pertanyaan"
                                value="{{ isset($surveipertanyaan) ? old('pertanyaan',$surveipertanyaan->pertanyaan) : old('pertanyaan') }}"
                                placeholder="Masukkan Pertanyaan" />
                            @error('pertanyaan')
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

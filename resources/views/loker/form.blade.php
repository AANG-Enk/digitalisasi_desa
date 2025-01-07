@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('title')
    @if (isset($lokerrw))
        Edit Lowongan Kerja - Digitalisasi Desa
    @else
        Tambah Lowongan Kerja - Digitalisasi Desa
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
                <a href="{{ route('lokerrw.index') }}">Daftar Lowongan Kerja</a>
            </li>
            @if (isset($lokerrw))
                <li class="breadcrumb-item active">Edit Lowongan Kerja {{ $lokerrw->name }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Lowongan Kerja</li>
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
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="form-area">
                    @isset($lokerrw) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="judul">Judul <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('judul') is-invalid @enderror"
                                id="judul"
                                name="judul"
                                value="{{ isset($lokerrw) ? old('judul',$lokerrw->judul) : old('judul') }}"
                                placeholder="Masukkan Judul Lowongan Kerja" />
                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="perusahaan">Tempat Lowongan Kerja <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('perusahaan') is-invalid @enderror"
                                id="perusahaan"
                                name="perusahaan"
                                value="{{ isset($lokerrw) ? old('perusahaan',$lokerrw->perusahaan) : old('perusahaan') }}"
                                placeholder="Masukkan Nama Tempat Lowongan Kerja" />
                            @error('perusahaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="posisi">Posisi Lowongan Kerja <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('posisi') is-invalid @enderror"
                                id="posisi"
                                name="posisi"
                                value="{{ isset($lokerrw) ? old('posisi',$lokerrw->posisi) : old('posisi') }}"
                                placeholder="Masukkan Posisi Lowongan Kerja" />
                            @error('posisi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="hubungi">No. Whatsapp <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="number"
                                class="form-control @error('hubungi') is-invalid @enderror"
                                id="hubungi"
                                name="hubungi"
                                value="{{ isset($lokerrw) ? old('hubungi',$lokerrw->hubungi) : old('hubungi') }}"
                                placeholder="Masukkan Nomor Whtasapp" />
                            <span class="text-secondary">Contoh : 62896050161</span>
                            @error('hubungi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div id="snow-toolbar">
                                <span class="ql-formats">
                                  <select class="ql-font"></select>
                                  <select class="ql-size"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-bold"></button>
                                  <button class="ql-italic"></button>
                                  <button class="ql-underline"></button>
                                  <button class="ql-strike"></button>
                                </span>
                                <span class="ql-formats">
                                  <select class="ql-color"></select>
                                  <select class="ql-background"></select>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-script" value="sub"></button>
                                  <button class="ql-script" value="super"></button>
                                </span>
                                <span class="ql-formats">
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                </span>
                                <span class="ql-formats">
                                  <button class="ql-header" value="1"></button>
                                  <button class="ql-header" value="2"></button>
                                  <button class="ql-blockquote"></button>
                                  <button class="ql-code-block"></button>
                                </span>
                            </div>
                            <div id="editor-container" style="height: 300px;" class="@error('deskripsi') is-invalid @enderror">
                                {!! isset($lokerrw) ? old('deskripsi',$lokerrw->deskripsi) : old('deskripsi') !!}
                            </div>
                            <textarea id="content" name="deskripsi" style="display: none;">{!! isset($lokerrw) ? old('deskripsi',$lokerrw->deskripsi) : old('deskripsi') !!}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="thumb">Thumbnail </label>
                        <div class="col-sm-10">
                            <input
                                type="file"
                                class="form-control @error('thumb') is-invalid @enderror"
                                id="thumb"
                                name="thumb"
                                placeholder="Masukkan Thumbnail" />
                            @error('thumb')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            @if (isset($lokerrw))
                                <img src="{{ asset('storage/'.$lokerrw->image) }}" alt="Thumbnail Lowongan kerja {{ $lokerrw->judul }}" style="max-width: 150px;">
                            @endif
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
<script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){
            var quill = new Quill('#editor-container', {
                bounds: '#editor-container',
                modules: {
                formula: true,
                    toolbar: '#snow-toolbar'
                },
                theme: 'snow'
            });

            // Simpan data Quill ke textarea sebelum submit
            var form = document.querySelector('#form-area');
            form.onsubmit = function() {
                var content = document.querySelector('textarea[name=deskripsi]');
                content.value = quill.root.innerHTML;
            };
        })

    </script>
@endsection

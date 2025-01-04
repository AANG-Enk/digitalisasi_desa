@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('title')
    @if (isset($inforw))
        Edit Informasi {{ $inforw->judul }} - Digitalisasi Desa
    @else
        Tambah Informasi - Digitalisasi Desa
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
                <a href="{{ route('inforw.index') }}">Info RW</a>
            </li>
            @if (isset($inforw))
                <li class="breadcrumb-item active">Edit Informasi {{ $inforw->judul }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Informasi</li>
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
                    @isset($inforw) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="judul">Judul <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('judul') is-invalid @enderror"
                                id="judul"
                                name="judul"
                                value="{{ isset($inforw) ? old('judul',$inforw->judul) : old('judul') }}"
                                placeholder="Masukkan Judul" />
                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="keterangan">Keterangan <span class="text-danger">*</span></label>
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
                                {!! isset($inforw) ? old('deskripsi',$inforw->deskripsi) : old('deskripsi') !!}
                            </div>
                            <textarea id="content" name="deskripsi" style="display: none;">{!! isset($inforw) ? old('deskripsi',$inforw->deskripsi) : old('deskripsi') !!}</textarea>
                            @error('deskripsi')
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

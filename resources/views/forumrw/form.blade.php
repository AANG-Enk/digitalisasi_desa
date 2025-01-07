@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    @if (isset($forumrw))
        Edit Thread {{ $forumrw->judul }} - Digitalisasi Desa
    @else
        Tambah Thread - Digitalisasi Desa
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
                <a href="{{ route('forumrw.index') }}">Forum RW</a>
            </li>
            @if (isset($forumrw))
                <li class="breadcrumb-item active">Edit Thread {{ $forumrw->judul }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Thread</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Tambah Thread</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}" id="form-area" enctype="multipart/form-data">
                    @isset($forumrw) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="judul">Judul <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('judul') is-invalid @enderror"
                                id="judul"
                                name="judul"
                                value="{{ isset($forumrw) ? old('judul',$forumrw->judul) : old('judul') }}"
                                placeholder="Masukkan Judul" />
                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="published_at">Tanggal Ditayangkan <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('published_at') is-invalid @enderror"
                                id="published_at"
                                name="published_at"
                                value="{{ isset($forumrw) ? old('published_at',\Carbon\Carbon::parse($forumrw->published_at)->isoFormat('DD-MM-YYYY')) : old('published_at') }}"
                                placeholder="Masukkan Tanggal" />
                            @error('published_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="thumb">Thumbnail <span class="text-danger">*</span></label>
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
                            @if (isset($forumrw))
                                <img src="{{ asset('storage/'.$forumrw->image) }}" alt="Thumbnail Berita {{ $forumrw->judul }}" style="max-width: 150px;">
                            @endif
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="keterangan">Deskripsi <span class="text-danger">*</span></label>
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
                                {!! isset($forumrw) ? old('deskripsi',$forumrw->deskripsi) : old('deskripsi') !!}
                            </div>
                            <textarea id="content" name="deskripsi" style="display: none;">{!! isset($forumrw) ? old('deskripsi',$forumrw->deskripsi) : old('deskripsi') !!}</textarea>
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
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
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

            const select2 = $('.select2');
            if (select2.length) {
                select2.each(function () {
                    var $this = $(this);
                    select2Focus($this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Kategori Berita',
                        dropdownParent: $this.parent()
                    });
                });
            }

            var datePicker = $('#published_at')
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

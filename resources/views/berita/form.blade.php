@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    @if (isset($beritum))
        Edit Berita {{ $beritum->judul }} - Digitalisasi Desa
    @else
        Tambah Berita - Digitalisasi Desa
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
                <a href="{{ route('berita.index') }}">Info RW</a>
            </li>
            @if (isset($beritum))
                <li class="breadcrumb-item active">Edit Berita {{ $beritum->judul }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Berita</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Tambah Berita</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}" id="form-area" enctype="multipart/form-data">
                    @isset($beritum) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="kategori_berita_id">Kategori <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select
                              id="select2Basic"
                              name="kategori_berita_id"
                              class="select2 form-select form-select-lg @error('kategori_berita_id') is-invalid @enderror"
                              data-allow-clear="true">
                                <option value="">Pilih Kategori</option>
                                @foreach ($list_kategori_berita as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ (isset($beritum) && $beritum->kategori_berita_id == $item->id) ? 'selected' : ((!is_null(old('kategori_berita_id')) && old('kategori_berita_id') == $item->id) ? 'selected' : '') }}
                                        >{{ $item->judul }}</option>
                                @endforeach
                            </select>
                            @error('kategori_berita_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="judul">Judul <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('judul') is-invalid @enderror"
                                id="judul"
                                name="judul"
                                value="{{ isset($beritum) ? old('judul',$beritum->judul) : old('judul') }}"
                                placeholder="Masukkan Judul" />
                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="published_at">Pin <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('pin') is-invalid @enderror"
                                  type="radio"
                                  name="pin"
                                  id="ya"
                                  {{ (isset($beritum) && $beritum->pin) ? 'checked' : ((!is_null(old('pin') && old('pin'))) ? 'checked' : '') }}
                                  value="1" />
                                <label class="form-check-label" for="ya">Ya</label>
                            </div>
                            <div class="form-check form-check-inline mt-4">
                                <input
                                  class="form-check-input @error('pin') is-invalid @enderror"
                                  type="radio"
                                  name="pin"
                                  id="tidak"
                                  {{ (isset($beritum) && !$beritum->pin) ? 'checked' : ((!is_null(old('pin') && !old('pin'))) ? 'checked' : '') }}
                                  value="0" />
                                <label class="form-check-label" for="tidak">Tidak</label>
                            </div>
                            @error('pin')
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
                                value="{{ isset($beritum) ? old('published_at',\Carbon\Carbon::parse($beritum->published_at)->isoFormat('DD-MM-YYYY')) : old('published_at') }}"
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
                            @if (isset($beritum))
                                <img src="{{ asset('storage/'.$beritum->image) }}" alt="Thumbnail Berita {{ $beritum->judul }}" style="max-width: 150px;">
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
                                {!! isset($beritum) ? old('deskripsi',$beritum->deskripsi) : old('deskripsi') !!}
                            </div>
                            <textarea id="content" name="deskripsi" style="display: none;">{!! isset($beritum) ? old('deskripsi',$beritum->deskripsi) : old('deskripsi') !!}</textarea>
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

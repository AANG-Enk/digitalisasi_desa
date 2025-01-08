@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    @if (isset($adsrw))
        Edit Ads RW {{ $adsrw->judul }} - Digitalisasi Desa
    @else
        Tambah Ads RW - Digitalisasi Desa
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
                <a href="{{ route('adsrw.index') }}">Ads RW</a>
            </li>
            @if (isset($adsrw))
                <li class="breadcrumb-item active">Edit Ads RW {{ $adsrw->judul }}</li>
            @else
                <li class="breadcrumb-item active">Tambah Ads RW</li>
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
                    @isset($adsrw) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="judul">Judul <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('judul') is-invalid @enderror"
                                id="judul"
                                name="judul"
                                value="{{ isset($adsrw) ? old('judul',$adsrw->judul) : old('judul') }}"
                                placeholder="Masukkan Judul" />
                            @error('judul')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="hubungi">No. Whatsapp</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('hubungi') is-invalid @enderror"
                                id="hubungi"
                                name="hubungi"
                                value="{{ isset($adsrw) ? old('hubungi',$adsrw->hubungi) : old('hubungi') }}"
                                placeholder="Masukkan No. Whatsapp" />
                                <span class="text-secondary">Contoh : 62896050161</span>
                            @error('hubungi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="link">Link</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('link') is-invalid @enderror"
                                id="link"
                                name="link"
                                value="{{ isset($adsrw) ? old('link',$adsrw->link) : old('link') }}"
                                placeholder="Masukkan Link Iklan" />
                            @error('link')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="start">Periode Mulai Ditayangkan <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('start') is-invalid @enderror tanggal"
                                id="start"
                                name="start"
                                value="{{ isset($adsrw) ? old('start',\Carbon\Carbon::parse($adsrw->start)->isoFormat('DD-MM-YYYY')) : old('start') }}"
                                placeholder="Masukkan Tanggal" />
                            @error('start')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="end">Periode Berakhir Ditayangkan <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('end') is-invalid @enderror tanggal"
                                id="end"
                                name="end"
                                value="{{ isset($adsrw) ? old('end',\Carbon\Carbon::parse($adsrw->end)->isoFormat('DD-MM-YYYY')) : old('end') }}"
                                placeholder="Masukkan Tanggal" />
                            @error('end')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="thumb">Thumbnail Ads <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="file"
                                class="form-control @error('thumb') is-invalid @enderror"
                                id="thumb"
                                name="thumb"
                                placeholder="Masukkan Thumbnail Ads RW" />
                            @error('thumb')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            @if (isset($adsrw))
                                <img src="{{ asset('storage/'.$adsrw->image) }}" alt="Thumbnail Ads RW {{ $adsrw->judul }}" style="max-width: 150px;">
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
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){

            var datePicker = $('.tanggal')
            if (datePicker.length) {
                datePicker.each(function(){
                    let $this = $(this);
                    $this.datepicker({
                        todayHighlight: true,
                        format: 'dd-mm-yyyy',
                        orientation: isRtl ? 'auto right' : 'auto left'
                    });
                })
            }
        })

    </script>
@endsection

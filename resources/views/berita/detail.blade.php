@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('title')
    {{ $berita->judul }} - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('berita.index') }}">Daftar Berita</a>
            </li>
            <li class="breadcrumb-item active">{{ $berita->judul }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
      <div class="card mb-6">
        <div class="user-profile-header-banner">
          <img src="{{ asset('storage/'.$berita->image) }}" alt="Thumbnail Berita {{ $berita->judul }}" class="rounded-top" />
        </div>
        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-5">
          <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
            @if (!is_null($berita->pembuat->foto))
                <img
                src="{{ asset('storage/'.$berita->pembuat->foto) }}"
                alt="user image"
                class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img" />
            @else
                <img
                src="{{ asset('assets/img/logo/logo.png') }}"
                alt="user image"
                class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img" />
            @endif
          </div>
          <div class="flex-grow-1 mt-4 mt-sm-12">
            <div
              class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-6">
              <div class="user-profile-info">
                <h4 class="mb-2">{{ $berita->pembuat->name }}</h4>
                <ul
                  class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                  <li class="list-inline-item">
                    <i class="ri-book-shelf-line me-2 ri-24px"></i><span class="fw-medium">{{ $berita->kategori->judul }}</span>
                  </li>
                  <li class="list-inline-item">
                    <i class="ri-calendar-line me-2 ri-24px"></i
                    ><span class="fw-medium"> {{ \Carbon\Carbon::parse($berita->published_at)->isoFormat('DD-MMMM-YYYY') }}</span>
                  </li>
                </ul>
              </div>
              <a href="{{ route('berita.index') }}" class="btn btn-danger">
                <i class="ri-arrow-left-line ri-16px me-2"></i>Kembali
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">
                  {{ $berita->judul }}
                </h5>
            </div>
            <div class="card-body">
                {!! $berita->deskripsi !!}
            </div>
        </div>
    </div>
</div>

@endsection

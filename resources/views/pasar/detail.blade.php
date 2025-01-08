@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('title')
    {{ $pasarrw->judul }} - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('pasarrw.index') }}">Daftar Produk Pasar RW</a>
            </li>
            <li class="breadcrumb-item active">{{ $pasarrw->judul }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
      <div class="card mb-6">
        <div class="user-profile-header-banner">
          <img src="{{ asset('storage/'.$pasarrw->image) }}" alt="Thumbnail Produk Pasar RW {{ $pasarrw->judul }}" class="rounded-top" />
        </div>
        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-5">
          <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
            <img
              src="{{ asset('assets/img/avatars/1.png') }}"
              alt="user image"
              class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img" />
          </div>
          <div class="flex-grow-1 mt-4 mt-sm-12">
            <div
              class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-6">
              <div class="user-profile-info">
                <h4 class="mb-2">{{ $pasarrw->pembuat->name }}</h4>
                <ul
                  class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                  <li class="list-inline-item">
                    <i class="ri-calendar-line me-2 ri-24px"></i
                    ><span class="fw-medium"> {{ \Carbon\Carbon::parse($pasarrw->created_at)->isoFormat('DD-MMMM-YYYY') }}</span>
                  </li>
                </ul>
              </div>
              <a href="{{ route('pasarrw.index') }}" class="btn btn-danger">
                <i class="ri-arrow-left-line ri-16px me-2"></i>Kembali
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-action-title mb-0">
                  {{ $pasarrw->judul }}
                </h4>
                <a target="_blank" href="https://wa.me/{{ $pasarrw->hubungi }}?text=Halo,%20saya%20tertarik%20dengan%20produk%20ini:%20{{ route('pasarrw.show',$pasarrw->slug) }}" class="btn btn-success">Pesan Via Whatsapp</a>
            </div>
            <div class="card-body">
                <p> Harga : Rp. {{ number_format($pasarrw->harga, 2, ',', '.') }}</p>
            </div>
            <div class="card-footer">
                {!! $pasarrw->deskripsi !!}
            </div>
        </div>
    </div>
</div>

@endsection

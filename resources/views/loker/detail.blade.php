@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('title')
    {{ $lokerrw->judul }} - Digitalisasi Desa
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
            <li class="breadcrumb-item active">{{ $lokerrw->judul }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
      <div class="card mb-6">
        <div class="user-profile-header-banner">
            @if (!is_null($lokerrw->image))
                <img src="{{ asset('storage/'.$lokerrw->image) }}" alt="Thumbnail Lowongan Kerja {{ $lokerrw->judul }}" class="rounded-top" />
            @else
                <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Thumbnail Lowongan Kerja {{ $lokerrw->judul }}" class="rounded-top" />
            @endif
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
                <h4 class="mb-2">{{ $lokerrw->pembuat->name }}</h4>
                <ul
                  class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                  <li class="list-inline-item">
                    <i class="ri-calendar-line me-2 ri-24px"></i
                    ><span class="fw-medium"> {{ \Carbon\Carbon::parse($lokerrw->created_at)->isoFormat('DD-MMMM-YYYY') }}</span>
                  </li>
                </ul>
              </div>
              <a href="{{ route('lokerrw.index') }}" class="btn btn-danger">
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
                  {{ $lokerrw->judul }}
                </h5>
            </div>
            <div class="card-body">
                <table class="table mb-3">
                    <tr>
                        <th>Tempat Bekerja</th>
                        <td>:</td>
                        <td>{{ $lokerrw->perusahaan }}</td>
                    </tr>
                    <tr>
                        <th>Posisi</th>
                        <td>:</td>
                        <td>{{ $lokerrw->posisi }}</td>
                    </tr>
                    <tr>
                        <th>Hubungi</th>
                        <td>:</td>
                        <td><a target="_blank" href="https://wa.me/{{ $lokerrw->hubungi }}" class="btn btn-success">Via Whatsapp</a></td>
                    </tr>
                </table>
                {!! $lokerrw->deskripsi !!}
            </div>
        </div>
    </div>
  </div>

@endsection

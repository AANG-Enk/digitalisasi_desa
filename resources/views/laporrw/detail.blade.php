@extends('layouts.backend.main')

@section('title')
    Laporan RW {{ $laporrw->judul }} - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('laporrw.index') }}">Lapor RW</a>
            </li>
            <li class="breadcrumb-item active">{{ $laporrw->judul }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">
                  {{ $laporrw->judul }}
                </h5>
            </div>
            <div class="card-body">
                {!! $laporrw->deskripsi !!}
            </div>
        </div>
    </div>
    <div class="col-12 d-flex justify-content-end mt-4">
        <a href="{{ route('laporrw.dibaca',$laporrw->slug) }}" class="btn btn-success">Sudah Dibaca</a>
    </div>
</div>

@endsection

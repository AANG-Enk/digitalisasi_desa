@extends('layouts.backend.main')

@section('title')
    Informasi {{ $inforw->judul }} - Digitalisasi Desa
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
            <li class="breadcrumb-item active">{{ $inforw->judul }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center">
                <h3 class="card-action-title mb-0 text-center">
                  {{ $inforw->judul }}
                </h3>
            </div>
            <div class="card-body">
                {!! $inforw->deskripsi !!}
            </div>
        </div>
    </div>
</div>

@endsection

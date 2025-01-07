@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('title')
    Daftar Lowongan Kerja - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Daftar Lowongan Kerja</li>
        </ol>
    </nav>
</div>

<div class="row mb-12 g-6">
    @foreach ($list_loker as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                @if (!is_null($item->image))
                    <img class="card-img-top" src="{{ asset('assets/img/'.$item->image) }}" alt="Card image cap" />
                @else
                    <img class="card-img-top" src="{{ asset('assets/img/logo/logo.png') }}" alt="Card image cap" />
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ \Illuminate\Support\Str::limit($item->judul, 30) }}</h5>
                    <p class="card-text">
                        {{ \Illuminate\Support\Str::of(strip_tags($item->deskripsi))->words(10, '...') }}
                    </p>
                    <a href="{{ route('lokerrw.show',$item->slug) }}" class="btn btn-outline-primary">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-sm-12 d-flex justify-content-end">
        <nav aria-label="Page navigation">
            {!! $list_loker->links() !!}
        </nav>
    </div>
</div>

@endsection

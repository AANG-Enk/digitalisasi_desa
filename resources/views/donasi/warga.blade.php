@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('title')
    Daftar Donasi RW - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Daftar Donasi RW</li>
        </ol>
    </nav>
</div>

<div class="row mb-12 g-6">
    @foreach ($list_donasi as $item)
        <div class="col-md-6 col-lg-4 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->judul }}</h5>
                    <p class="card-text">
                        {{ \Illuminate\Support\Str::of(strip_tags($item->deskripsi))->words(10, '...') }}
                    </p>
                    <table>
                        <tr>
                            <th>Target</th>
                            <td>:</td>
                            <td>Rp. {{ number_format($item->target,2,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Didonasikan</th>
                            <td>:</td>
                            <td>Rp. {{ number_format($item->bayars_sum_nominal,2,',','.') }}</td>
                        </tr>
                    </table>
                    <div class="d-flex justify-content-center mt-2">
                        <a href="{{ route('donasirw.bayardonasi.index',$item->slug) }}" class="btn btn-outline-primary">Donasi</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-sm-12 d-flex justify-content-end">
        <nav aria-label="Page navigation">
            {!! $list_donasi->links() !!}
        </nav>
    </div>
</div>

@endsection

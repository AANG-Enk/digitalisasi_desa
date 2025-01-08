@extends('layouts.backend.main')

@section('title')
    Produk Pasar RW - Digitalisasi Desa
@endsection

@section('content')
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Produk Pasar RW</li>
        </ol>
    </nav>
</div>

<div class="row row-cols-1 row-cols-md-3 g-6 mb-12">
    @foreach ($list_pasar as $item)
        <div class="col">
            <div class="card h-100">
                <img class="card-img-top" src="{{ asset('storage/'.$item->image) }}" alt="Thumbnail Produk {{ $item->judul }}" />
                <div class="card-body">
                    <h5 class="card-title">{{ Illuminate\Support\Str::limit($item->judul,50) }}</h5>
                    <p class="my-2">
                        Rp. {{ number_format($item->harga, 2, ',', '.') }}
                    </p>
                    <p class="card-text">
                        {{ strlen(strip_tags($item->deskripsi)) > 120 ? strip_tags(substr($item->deskripsi, 0, 120)) . '...' : strip_tags($item->deskripsi) }}
                    </p>
                    <ul
                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                        <li class="list-inline-item">
                            <i class="ri-user-line me-2 ri-24px"></i><span class="fw-medium">{{ $item->pembuat->name }}</span>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-center mt-4 gap-3">
                        <a href="{{ route('pasarrw.show',$item->slug) }}" class="btn btn-primary">Lihat Selengkapnya</a>
                        <a target="_blank" href="https://wa.me/{{ $item->hubungi }}" class="btn btn-success">Via Whatsapp</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-12 d-flex justify-content-center my-4">
        {!! $list_pasar->links() !!}
    </div>
</div>
@endsection

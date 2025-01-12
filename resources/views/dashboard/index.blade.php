@extends('layouts.backend.main')

@section('vendorcss')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
@endsection

@section('pagecss')
    <style>
        .card-img-top {
            object-fit: cover; /* Pastikan gambar proporsional */
            width: 100%; /* Pastikan gambar memenuhi card */
            height: 400px; /* Tetapkan tinggi seragam */
        }
    </style>
@endsection

@section('title')
    Dashboard - Digitalisasi Desa
@endsection

@section('content')
<div class="row g-6">

    <!-- Gamification Card -->
    <div class="col-md-12 col-xxl-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-md-6 order-2 order-md-1">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Selamat Datang Di<span class="fw-bold"> Digitalisasi Desa</span></h4>
                        <p class="mb-0">Silakan jelajahi fitur kami seperti layanan online, informasi potensi desa, dan berita terkini.</p>
                        <p>Nikmati pengalaman baru dalam pelayanan desa digital! 🌟</p>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                    <div class="card-body pb-0 px-0 pt-2">
                        <img
                        src="{{ asset('assets/img/illustrations/illustration-john-light.png') }}"
                        height="186"
                        class="scaleX-n1-rtl"
                        alt="View Profile"
                        data-app-light-img="illustrations/illustration-john-light.png"
                        data-app-dark-img="illustrations/illustration-john-dark.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Gamification Card -->

    <div class="col-md-12 col-xxl-12">
        @if ($list_iklan->isNotEmpty())
            <div class="swiper-container swiper-container-horizontal swiper h-100" id="swiper-weekly-sales-with-bg">
                <div class="swiper-wrapper">
                    @foreach ($list_iklan as $iklan)
                        <div class="swiper-slide pb-5">
                            <div class="card border-0 shadow-lg">
                                <img src="{{ asset('storage/'.$iklan->image) }}" class="card-img-top img-fluid rounded" alt="Iklan {{ $iklan->judul }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $iklan->judul }}</h5>
                                    <p class="card-text">{{ $iklan->deskripsi }}</p>
                                    @if (!is_null($iklan->hubungi))
                                        <a target="_blank" href="https://wa.me/{{ $iklan->hubungi }}" class="btn btn-primary">Hubungi Via Whatsapp</a>
                                    @elseif (!is_null($iklan->link))
                                        <a href="{{ $iklan->link }}" class="btn btn-primary">Klik Disini</a>
                                    @else
                                        <span class="badge bg-secondary">Hubungi Admin Untuk Info Lebih Lanjut</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card border-0 shadow-lg">
                <div class="card-body">
                    <h4 class="text-center">Tidak Ada Iklan Yang Aktif</h4>
                </div>
            </div>
        @endif
    </div>


</div>
@endsection

@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){

            const swiperWithBgPagination = document.querySelector('#swiper-weekly-sales-with-bg');
            if (swiperWithBgPagination) {
                new Swiper(swiperWithBgPagination, {
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false
                },
                pagination: {
                    clickable: true,
                    el: '.swiper-pagination'
                }
                });
            }

        })
    </script>
@endsection

@extends('layouts.backend.main')

@section('vendorcss')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
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
</div>
@endsection

@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){

            const swiperMarketingPagination = document.querySelector('#swiper-marketing-sales');
            if (swiperMarketingPagination) {
                new Swiper(swiperMarketingPagination, {
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

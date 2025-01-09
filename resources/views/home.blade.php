<!doctype html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-wide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets') }}/"
  data-template="front-pages"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Homepage {{ config('app.name') }}</title>

    <meta name="description" content="Mewujudkan Aplikasi Edisi Baru(Edukasi, Diskusi, Solusi Barudak RW Satu): Digitalisasi Data Kependudukan di Kantor RW.01 Kelurahan Babakan" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page.css') }}" />
    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/nouislider/nouislider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-landing.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script> --}}
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/front-config.js') }}"></script>
    <style>
        body {
            margin: 0;
            height: 100vh; /* Memastikan elemen body memenuhi layar penuh */
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f5d8e8, #d7e3fc, #c9f0f0);
            background-size: 200% 200%; /* Memberikan ukuran lebih besar untuk efek lembut */
            animation: gradient-animation 8s infinite; /* Animasi halus jika diinginkan */
        }

        h1 {
            color: #6a6eff;
            font-size: 2.5rem;
            text-align: center;
        }

        p {
            text-align: center;
            margin-top: 0.5rem;
            color: #63666a;
            font-size: 1.2rem;
        }

        button {
            margin-top: 1.5rem;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            background-color: #6a6eff;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5858d6;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

    </style>
  </head>

  <body>
    <script src="{{ asset('assets/vendor/js/dropdown-hover.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/mega-dropdown.js') }}"></script>

    <!-- Sections:Start -->
    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="landingHero" class="section-py landing-hero position-relative">
            <div class="container">
                <div class="hero-text-box text-center">
                    <h3 class="text-primary hero-title fs-2">Digitalisasi Desa</h3>
                    <h2 class="h6 mb-8">
                        Mewujudkan Aplikasi Edisi Baru(Edukasi, Diskusi, Solusi Barudak RW Satu): Digitalisasi Data Kependudukan di Kantor RW.01 Kelurahan Babakan Berbasis Web dengan Laravel
                    </h2>
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">Login</a>
                </div>
            </div>
        </section>
        <!-- Hero: End -->
    </div>
    <!-- / Sections:End -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/front-main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/front-page-landing.js') }}"></script>
  </body>
</html>

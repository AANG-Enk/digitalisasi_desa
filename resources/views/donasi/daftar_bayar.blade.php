@extends('layouts.backend.main')

@section('title')
    Daftar IREDA {{ $donasi->judul }} - Digitalisasi Desa
@endsection

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
@endsection

@section('content')
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('ireda.index') }}">Daftar IREDA</a>
          </li>
          <li class="breadcrumb-item active">{{ $donasi->judul }}</li>
        </ol>
    </nav>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="card mb-6">
    <div class="card-body pt-5">
        <h4>Total Donasi : RP. {{ number_format($list_bayar->sum('nominal'),2,',','.') }}</h4>
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-datadonasirw table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pembayaran</th>
                        <th>Nominal</th>
                        <th>Verifikasi</th>
                        <th>Bukti Pembayaran</th>
                        @can('Donasi RW Bukti')
                            <th>Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list_bayar as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->pembayaran }}</td>
                            <td>Rp. {{ number_format($item->nominal,2,',','.') }}</td>
                            <td>{{ ($item->is_verified)?'Sudah':'Belum' }}</td>
                            <td>
                                @if (!is_null($item->file))
                                    <img src="{{ asset('storage/'.$item->file) }}" alt="Gambar Bukti Pembayaran" class="img-fluid" style="max-width: 150px">
                                @else
                                    Belum Upload Bukti Pembaaran
                                @endif
                            </td>
                            @can('Donasi RW Bukti')
                                <td>
                                    <a href="{{ route('ireda.iuran.bukti.index',[$donasi->slug,$item->id]) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Upload Bukti {{ $item->name }}" class="text-success"><i class="menu-icon tf-icons ri-file-text-line"></i></a>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="my-3 d-flex justify-content-center gap-2">
            @can('Donasi RW Setor')
                <a href="{{ route('ireda.iuran.create',$donasi->slug) }}" class="btn btn-primary">Tambah Donasi</a>
            @endcan
        </div>
    </div>
</div>
@endsection

@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        document.addEventListener('DOMContentLoaded', function (e) {
            $(function () {
                var dt_basic_table = $('.datatables-datadonasirw'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-datadonasirw')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Daftar IREDA</h5>');
                    }
                }
            })

        })
    </script>
@show

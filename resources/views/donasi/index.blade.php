@extends('layouts.backend.main')

@section('title')
    Daftar IREDA - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Daftar IREDA</li>
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
    <div class="card-header p-0">
        <div class="nav-align-top">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-justified-home"
                        aria-controls="navs-justified-home"
                        aria-selected="true">
                        <span class="d-none d-sm-block"><i class="tf-icons ri-home-smile-line me-2"></i> IREDA</span>
                        <i class="ri-home-smile-line ri-20px d-sm-none"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-justified-profile"
                        aria-controls="navs-justified-profile"
                        aria-selected="false">
                        <span class="d-none d-sm-block"><i class="tf-icons ri-bank-card-line me-2"></i> Pembayaran</span>
                        <i class="ri-bank-card-line ri-20px d-sm-none"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body pt-5">
        <div class="tab-content p-0">
            <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-datadonasirw table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Pembuat</th>
                                <th>Akhir Periode</th>
                                <th>Target Donasi</th>
                                <th>Terkumpul</th>
                                @canany(['Donasi RW Update','Donasi RW Delete'])
                                    <th>Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_donasi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->pembuat->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->berakhir)->isoFormat('DD-MMMM-YYYY') }}</td>
                                    <td>Rp. {{ number_format($item->target, 2, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($item->bayars_sum_nominal,2,',','.') }}</td>
                                    @canany(['Donasi RW Update','Donasi RW Delete'])
                                        <td>
                                            <div class="d-flex gap-1">
                                                @can('Donasi RW Update')
                                                    <a href="{{ route('ireda.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                                @endcan
                                                @can('Donasi RW Delete')
                                                    <form method="post" action="{{ route('ireda.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Donasi RW, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="my-3 d-flex justify-content-center gap-2">
                    @can('Donasi RW Create')
                        <a href="{{ route('ireda.create') }}" class="btn btn-primary">Tambah IREDA</a>
                    @endcan
                </div>
            </div>
            <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-datadonasirw table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Donasi</th>
                                <th>Yang Bayar</th>
                                <th>Pembayaran</th>
                                <th>Nominal</th>
                                <th>Verifikasi</th>
                                <th>Bukti Pembayaran</th>
                                @canany(['Donasi RW Verifikasi'])
                                    <th>Aksi</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_bayar as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->donasi->judul }}</td>
                                    <td>{{ $item->bayar->name }}</td>
                                    <td>{{ $item->pembayaran }}</td>
                                    <td>Rp. {{ number_format($item->nominal,2,',','.') }}</td>
                                    <td>{{ ($item->is_verified)?'Sudah':'Belum' }}</td>
                                    <td>
                                        @if (!is_null($item->file))
                                            <img src="{{ asset('storage/'.$item->file) }}" alt="Gambar Verifikasi Pembayaran" class="img-fluid" style="max-width: 150px">
                                        @else
                                            Belum Upload Bukti Pembayaran
                                        @endif
                                    </td>
                                    @canany(['Donasi RW Verifikasi'])
                                        <td>
                                            @if (!is_null($item->file))
                                                @if (!$item->is_verified)
                                                    <form method="post" action="{{ route('ireda.iuran.verifikasi',[$item->donasi->slug,$item->id]) }}" id="form-verifikasi-{{ $loop->iteration }}" class="d-inline">
                                                        @csrf
                                                        <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Bukti Pembayaran Donasi {{ $item->donasi->judul }} Oleh Bapak/Ibu/Sdr/i {{ $item->bayar->name }} Sudah Sah', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-verifikasi-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Setujui Donasi {{ $item->bayar->name }}" class="text-success"><i class="menu-icon tf-icons ri-thumb-up-line"></i></a>
                                                    </form>
                                                @else
                                                    Sudah Diverifikasi
                                                @endif
                                            @else
                                                Belum Upload Bukti Pembayaran
                                            @endif
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
                        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Donasi RW</h5>');
                    }
                }
            })

        })
    </script>
@show

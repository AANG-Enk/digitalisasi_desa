@extends('layouts.backend.main')

@section('title')
    Daftar Donasi RW - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Daftar Donasi RW</li>
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
<div class="card">
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
                                        <a href="{{ route('donasirw.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                    @endcan
                                    @can('Donasi RW Delete')
                                        <form method="post" action="{{ route('donasirw.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
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
            <a href="{{ route('donasirw.create') }}" class="btn btn-primary">Tambah Donasi RW</a>
        @endcan
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

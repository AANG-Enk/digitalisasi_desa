@extends('layouts.backend.main')

@section('title')
    Data Warga - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Data Warga</li>
        </ol>
    </nav>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-datawarga table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>KK</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>RT</th>
                    <th>RW</th>
                    <th>HP</th>
                    <th>Status</th>
                    @canany(['Data Warga Update','Data Warga Delete'])
                        <th>Aksi</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($wargas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->kk }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->tempat_lahir }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->isoFormat('DD-MMMM-YYYY') }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->rt }}</td>
                        <td>{{ $item->rw }}</td>
                        <td>{{ $item->hp }}</td>
                        <td>{{ $item->status }}</td>
                        @canany(['Data Warga Update','Data Warga Delete'])
                            <td>
                                <div class="d-flex gap-1">
                                    @can('Data Warga Update')
                                        <a href="{{ route('datawarga.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                    @endcan
                                    @can('Data Warga Delete')
                                        <form method="post" action="{{ route('datawarga.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus data warga, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
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
        @can('Data Warga Create')
            <a href="{{ route('datawarga.create') }}" class="btn btn-primary">Tambah Data Warga</a>
            <a href="{{ route('datawarga.import.index') }}" class="btn btn-secondary">Import Data Warga</a>
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
                var dt_basic_table = $('.datatables-datawarga'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-datawarga')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Pengguna</h5>');
                    }
                }
            })

        })
    </script>
@show

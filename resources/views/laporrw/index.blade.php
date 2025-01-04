@extends('layouts.backend.main')

@section('title')
    Lapor RW - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Lapor RW</li>
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
        <table class="datatables-datalapor table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    @if (!auth()->user()->hasRole('Warga'))
                        <th>Pelapor</th>
                    @endif
                    <th>Judul</th>
                    <th>Dibaca</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_lapor_rw as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                            @if (!auth()->user()->hasRole('Warga'))
                                <th>{{ $item->pelapor->name }}</th>
                            @endif
                        <td>{{ $item->judul }}</td>
                        <td>{!! ($item->is_read)?'<span class="badge bg-success">Sudah Dibaca</span>':'<span class="badge bg-danger">Belum Dibaca</span>' !!}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('laporrw.show',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Laporan {{ $item->judul }}" class="text-success"><i class="menu-icon tf-icons ri-eye-line"></i></a>
                                @if (!$item->is_read && auth()->user()->id == $item->user_id)
                                    @can('Lapor RW Update')
                                        <a href="{{ route('laporrw.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->judul }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                    @endcan
                                    @can('Lapor RW Delete')
                                        <form method="post" action="{{ route('laporrw.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Lapor RW, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->judul }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
                                        </form>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="my-3 d-flex justify-content-center gap-2">
        @can('Lapor RW Create')
            <a href="{{ route('laporrw.create') }}" class="btn btn-primary">Tambah Lapor RW</a>
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
                var dt_basic_table = $('.datatables-datalapor'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-datalapor')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Lapor RW</h5>');
                    }
                }
            })

        })
    </script>
@show

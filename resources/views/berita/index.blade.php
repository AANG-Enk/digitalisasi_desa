@extends('layouts.backend.main')

@section('title')
    Berita - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Berita</li>
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
        <table class="datatables-databerita table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Thumbnail</th>
                    <th>Judul</th>
                    <th>Pembuat</th>
                    <th>Kategori</th>
                    <th>Ditayangkan</th>
                    <th>Pin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_berita as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ asset('storage/'.$item->image) }}" alt="Thumbnail Berita {{ $item->judul }}" class="img-fluid" style="width: 150px;">
                        </td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->pembuat->name }}</td>
                        <td>{{ $item->kategori->judul }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->published_at)->isoFormat('DD-MMMM-YYYY') }}</td>
                        <td>{!! ($item->pin)?'<span class="badge bg-success">Ya</span>':'<span class="badge bg-danger">Tidak</span>' !!}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('berita.show',$item->slug) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Detail {{ $item->name }}" class="text-success"><i class="menu-icon tf-icons ri-eye-line"></i></a>
                                @can('Berita RW Update')
                                    <a href="{{ route('berita.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                @endcan
                                @can('Berita RW Delete')
                                    <form method="post" action="{{ route('berita.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Berita, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="my-3 d-flex justify-content-center gap-2">
        @can('Berita RW Create')
            <a href="{{ route('berita.create') }}" class="btn btn-primary">Tambah Berita</a>
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
                var dt_basic_table = $('.datatables-databerita'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-databerita')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Berita</h5>');
                    }
                }
            })

        })
    </script>
@show

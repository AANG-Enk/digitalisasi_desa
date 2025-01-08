@extends('layouts.backend.main')

@section('title')
    Pasar RW - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Pasar RW</li>
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
        <table class="datatables-datapasar table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Thumbnail</th>
                    <th>Judul</th>
                    <th>Harga</th>
                    <th>Hubungi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_pasar as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if (!is_null($item->image))
                                <img src="{{ asset('storage/'.$item->image) }}" alt="Thumbnail Produk Pasar RW {{ $item->judul }}" class="img-fluid" style="width: 150px;">
                            @else
                                Tidak Ada Gambar
                            @endif
                        </td>
                        <td>{{ $item->judul }}</td>
                        <td>Rp. {{ number_format($item->harga, 2, ',', '.') }}</td>
                        <td><a target="_blank" href="https://wa.me/{{ $item->hubungi }}" class="btn btn-success">Via Whatsapp</a></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('pasarrw.show',$item->slug) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Detail {{ $item->judul }}" class="text-success"><i class="menu-icon tf-icons ri-eye-line"></i></a>
                                @can('Pasar RW Update')
                                    <a href="{{ route('pasarrw.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->judul }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                @endcan
                                @can('Pasar RW Delete')
                                    <form method="post" action="{{ route('pasarrw.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Produk Pasar RW, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->judul }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
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
        @can('Pasar RW Create')
            <a href="{{ route('pasarrw.create') }}" class="btn btn-primary">Tambah Produk Pasar RW</a>
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
                var dt_basic_table = $('.datatables-datapasar'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-datapasar')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0" Produk>Pasar RW</h5>');
                    }
                }
            })

        })
    </script>
@show

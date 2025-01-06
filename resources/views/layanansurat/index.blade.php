@extends('layouts.backend.main')

@section('title')
    Layanan Surat - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Layanan Surat</li>
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
        <table class="datatables-datarw table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pembuat</th>
                    <th>Surat Tujuan</th>
                    <th>Nomor Registrasi RT</th>
                    <th>Nomor Registrasi RW</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_layanan_surat as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->pembuat->name }}</td>
                        <td>{{ $item->tujuan->nama }}</td>
                        <td>{{ (is_null($item->nomor_surat_rt))?'Belum Dikasih Nomor':$item->nomor_surat_rt }}</td>
                        <td>{{ (is_null($item->nomor_surat_rw))?'Belum Dikasih Nomor':$item->nomor_surat_rw }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a target="_blank" href="{{ route('layanansurat.print',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Print Surat {{ $item->pembuat->name }}" class="text-primary"><i class="menu-icon tf-icons ri-printer-line"></i></a>
                                @if (is_null($item->nomor_surat_rt) && auth()->user()->can('Layanan Surat Update') && auth()->user()->ketua_rt)
                                    <a href="{{ route('layanansurat.nomorrt.index',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kasih Nomor RT Surat {{ $item->pembuat->name }}" class="text-primary"><i class="menu-icon tf-icons ri-file-warning-line"></i></a>
                                @endif
                                @if (!auth()->user()->hasRole('Warga'))
                                    @if (is_null($item->nomor_surat_rw) && auth()->user()->can('Layanan Surat Update'))
                                        <a href="{{ route('layanansurat.nomorrw.index',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Kasih Nomor RW Surat {{ $item->pembuat->name }}" class="text-warning"><i class="menu-icon tf-icons ri-file-check-line"></i></a>
                                    @endif
                                @endif
                                @if (auth()->user()->id == $item->user_id && is_null($item->nomor_surat_rt) || is_null($item->nomor_surat_rw))
                                    @can('Layanan Surat Update')
                                        <a href="{{ route('layanansurat.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                    @endcan
                                    @can('Layanan Surat Delete')
                                        <form method="post" action="{{ route('layanansurat.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Surat, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
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
        @can('Layanan Surat Create')
            <a href="{{ route('layanansurat.create') }}" class="btn btn-primary">Tambah Surat Baru</a>
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
                var dt_basic_table = $('.datatables-datarw'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-datarw')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Info RW</h5>');
                    }
                }
            })

        })
    </script>
@show

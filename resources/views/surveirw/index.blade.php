@extends('layouts.backend.main')

@section('title')
    Survei RW - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Survei RW</li>
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
        <table class="datatables-datarw table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_survei as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                @can('Survei RW Jawaban Access')
                                    @if (auth()->user()->hasRole('Warga'))
                                        <a href="{{ route('surveirw.jawaban.index',$item->slug) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Isi Survei {{ $item->judul }}" class="text-primary"><i class="menu-icon tf-icons ri-question-answer-line"></i></a>
                                    @else
                                        <a href="{{ route('surveirw.warga',$item->slug) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Warga {{ $item->judul }}" class="text-primary"><i class="menu-icon tf-icons ri-question-answer-line"></i></a>
                                    @endif
                                @endcan
                                @can('Survei RW Pertanyaan Access')
                                    <a href="{{ route('surveirw.pertanyaan.index',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Pertanyaan {{ $item->judul }}" class="text-success"><i class="menu-icon tf-icons ri-questionnaire-line"></i></a>
                                @endcan
                                @can('Survei RW Update')
                                    <a href="{{ route('surveirw.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->judul }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                @endcan
                                @can('Survei RW Delete')
                                    <form method="post" action="{{ route('surveirw.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Survei RW, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->judul }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
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
        @can('Survei RW Create')
            <a href="{{ route('surveirw.create') }}" class="btn btn-primary">Tambah Survei RW</a>
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
                        $('div.head-label').html('<h5 class="card-title mb-0">Survei RW</h5>');
                    }
                }
            })

        })
    </script>
@show

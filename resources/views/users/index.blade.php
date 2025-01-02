@extends('layouts.backend.main')

@section('title')
    Users Management - Digitalisasi Desa
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
          <li class="breadcrumb-item active">Users Management</li>
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
        <table class="datatables-users table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Banned</th>
                    @canany(['User Update','User Delete'])
                        <th>Action</th>
                    @endcanany
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->roles()->first()->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->email_verified_at)->isoFormat('DD-MMMM-YYYY') }}</td>
                        <td>{!! ($item->banned) ? '<span class="badge bg-danger">Dibanned</span>' : '<span class="badge bg-success">Not Banned</span>' !!}</td>
                        @canany(['User Update','User Delete'])
                            <td>
                                <div class="d-flex gap-1">
                                    @can('User Update')
                                        <a href="{{ route('users.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->name }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                    @endcan
                                    @can('User Banned')
                                        @if ($item->banned)
                                        <a href="{{ route('users.unbanned',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Aktifkan {{ $item->name }}" class="text-success"><i class="menu-icon tf-icons ri-check-double-line"></i></a>
                                        @else
                                            <a href="{{ route('users.banned',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nonaktifkan {{ $item->name }}" class="text-warning"><i class="menu-icon tf-icons ri-spam-3-line"></i></a>
                                        @endif
                                    @endcan
                                    @can('User Delete')
                                        <form method="post" action="{{ route('users.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus akun pengguna, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
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
    <div class="my-3 d-flex justify-content-center">
        @can('User Create')
            <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Pengguna</a>
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
                var dt_basic_table = $('.datatables-users'), dt_basic;
                if (dt_basic_table.length) {
                    if (!$.fn.DataTable.isDataTable('.datatables-users')) {
                        dt_basic = dt_basic_table.DataTable()
                        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Pengguna</h5>');
                    }
                }
            })

        })
    </script>
@show

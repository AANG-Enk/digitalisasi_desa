@extends('layouts.backend.main')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('title')
    @if (isset($user))
        Forum RW {{ $user->name }} - Digitalisasi Desa
    @else
        Forum RW Pengurus - Digitalisasi Desa
    @endif
@endsection

@section('content')
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">@if (isset($user)) Forum RW {{ $user->name }} @else Forum RW Pengurus @endif</li>
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
<div class="row">
    <div class="col-12">
      <div class="card mb-6">
        <div class="user-profile-header-banner">
          <img src="{{ asset('assets/img/banner/banner_no_image.png') }}" alt="Banner Forum" class="rounded-top" />
        </div>
        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-5">
          <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
            <img
              src="{{ asset('assets/img/logo/logo.png') }}"
              alt="user image"
              class="d-block h-auto ms-0 ms-sm-5 rounded-4 user-profile-img" />
          </div>
          <div class="flex-grow-1 mt-4 mt-sm-12">
            <div
              class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-6">
                <div class="user-profile-info">
                    <h4 class="mb-2">{{ auth()->user()->name }}</h4>
                    <ul
                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                        <li class="list-inline-item">
                            <i class="ri-book-shelf-line ri-24px"></i><span class="fw-medium">Thread Saya : {{ $forum_saya }}</span>
                        </li>
                        <li class="list-inline-item">
                            <i class="ri-calendar-line ri-24px"></i
                            ><span class="fw-medium"> {{ \Carbon\Carbon::now()->isoFormat('DD-MMMM-YYYY H:mm') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('forumrw.pengurus.index') }}" class="btn btn-info">
                    <i class="ri-focus-2-line ri-16px me-2"></i>Lihat Thread
                </a>
                <a href="{{ route('forum.pengurus.saya') }}" class="btn btn-info">
                    <i class="ri-eye-line ri-16px me-2"></i>Thread Saya
                </a>
                @can('Forum RW Create')
                    <a href="{{ route('forumrw.pengurus.create') }}" class="btn btn-primary">
                        <i class="ri-add-line ri-16px me-2"></i>Thread Baru
                    </a>
                @endcan
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">
                  Daftar Thread
                </h5>
            </div>
            <div class="card-body">
                @foreach ($list_forum as $item)
                    <div class="row mb-2 border-bottom align-items-center">
                        <div class="col-md-3 d-none d-md-block">
                            <div class="d-flex border rounded flex-column align-items-center">
                                <img
                                src="{{ asset('assets/img/logo/logo.png') }}"
                                alt="user image"
                                class="img-thumbnail"
                                width="150"/>
                                <h6 class="fw-medium text-center">{{ $item->pembuat->name }}</h6>
                            </div>
                        </div>
                        <div class="col-md-3 col-12 order-md-3">
                            <img
                                src="{{ asset('storage/'.$item->image) }}"
                                alt="user image"
                                class="img-fluid img-thumbnail rounded"/>
                        </div>
                        <div class="col-md-6 col-12 order-md-2">
                            <div class="title mb-1">
                                <a href="{{ route('forumrw.pengurus.show',$item->slug) }}">
                                    <h4 class="text-left">{{ Illuminate\Support\Str::limit($item->judul,100) }}</h4>
                                </a>
                            </div>
                            <div class="text mb-2">
                                {{ strlen(strip_tags($item->deskripsi)) > 120 ? strip_tags(substr($item->deskripsi, 0, 120)) . '...' : strip_tags($item->deskripsi) }}
                                <div class="mt-2">
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4">
                                        <li class="list-inline-item">
                                            <i class="ri-eye-line ri-24px"></i><span class="fw-medium"></span>
                                        </li>
                                        <li class="list-inline-item">
                                            <i class="ri-calendar-line ri-24px"></i
                                            ><span class="fw-medium"> {{ \Carbon\Carbon::parse($item->published_at)->isoFormat('DD-MMMM-YYYY') }}</span>
                                        </li>
                                    </ul>
                                    <div class="d-flex justify-content-end gap-2">
                                        @if (isset($user))
                                            @can('Forum RW Update')
                                                <a href="{{ route('forumrw.pengurus.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->judul }}" class="text-secondary"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                                            @endcan
                                            @can('Forum RW Delete')
                                                <form method="post" action="{{ route('forumrw.pengurus.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Forum RW {{ $item->judul }}, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->name }}" class="text-danger"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
                                                </form>
                                            @endcan
                                        @endif
                                        <a href="{{ route('forumrw.pengurus.show',$item->slug) }}" class="btn btn-info btn-xs">Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-end">
                    <nav aria-label="Page navigation">
                        {!! $list_forum->links() !!}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

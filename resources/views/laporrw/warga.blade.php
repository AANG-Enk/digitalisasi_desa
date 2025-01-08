@extends('layouts.backend.main')

@section('title')
    Lapor RW - Digitalisasi Desa
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
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row g-6">
    @can('Lapor RW Create')
        <div class="col-12 d-flex justify-content-md-end">
            <a href="{{ route('laporrw.create') }}" class="btn btn-primary"><i class="menu-icon tf-icons ri-add-line"></i>Laporan Baru</a>
        </div>
    @endcan
    @if ($list_lapor_rw->isNotEmpty())
        @foreach ($list_lapor_rw as $item)
            <div class="col-12 col-md-4">
                <div class="card {{ ($item->is_read)?'bg-success':'bg-secondary' }} text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">{{ Illuminate\Support\Str::limit($item->judul,100) }}</h5>
                        <p class="card-text">
                            {{ strlen(strip_tags($item->deskripsi)) > 120 ? strip_tags(substr($item->deskripsi, 0, 120)) . '...' : strip_tags($item->deskripsi) }}
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('laporrw.show',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Laporan {{ $item->judul }}" class="text-white"><i class="menu-icon tf-icons ri-eye-line"></i></a>
                        @if (!$item->is_read && auth()->user()->id == $item->user_id)
                            @can('Lapor RW Update')
                                <a href="{{ route('laporrw.edit',$item->id) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit {{ $item->judul }}" class="text-white"><i class="menu-icon tf-icons ri-edit-2-line"></i></a>
                            @endcan
                            @can('Lapor RW Delete')
                                <form method="post" action="{{ route('laporrw.destroy',$item->id) }}" id="form-delete-{{ $loop->iteration }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <a href="javascript:void(0)" onclick="Swal.fire({ title: 'Apakah kamu yakin?', text: 'Hapus Lapor RW, data tidak bisa dikembalikkan', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus', customClass: { confirmButton: 'btn btn-primary me-3 waves-effect waves-light', cancelButton: 'btn btn-outline-secondary waves-effect' },}).then((willDelete) => { if (willDelete.value) { document.getElementById('form-delete-{{ $loop->iteration }}').submit(); } });" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete {{ $item->judul }}" class="text-white"><i class="menu-icon tf-icons ri-delete-bin-line"></i></a>
                                </form>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-12 d-flex justify-content-center my-4">
            {!! $list_lapor_rw->links() !!}
        </div>
    @else
        <div class="col-12">
            <h4 class="text-center">Tidak Ada Laporan Kepada RW</h4>
        </div>
    @endif
</div>
@endsection

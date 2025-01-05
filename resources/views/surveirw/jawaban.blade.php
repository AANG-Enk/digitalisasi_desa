@extends('layouts.backend.main')

@section('title')
    Jawaban {{ $survei->judul }} - Digitalisasi Desa
@endsection

@section('content')
<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('surveirw.index') }}">Survei RW</a>
          </li>
          @if (isset($action))
            <li class="breadcrumb-item active">{{ $survei->judul }}</li>
          @else
            <li class="breadcrumb-item">
                <a href="{{ route('surveirw.warga',$survei->slug) }}">{{ $survei->judul }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
          @endif
        </ol>
    </nav>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card">
    <div class="card-body">
        <h4 class="text-center">{{ $survei->judul }}</h4>
        <span class="text-danger">* wajib diisi</span>
        <form action="{{ (isset($action))?$action:'' }}" method="post">
            @csrf
            @php $x = 0; @endphp
            @foreach ($list_survei as $item)
                <input type="hidden" name="survei_pertanyaan_id[]" value="{{ $item->id }}">
                <div class="row mb-4">
                    <label class="col-sm-6 col-form-label" for="jawaban_{{ $x }}">
                        {{ $item->pertanyaan }} <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input
                            type="text"
                            class="form-control @error("jawaban.$x") is-invalid @enderror"
                            id="jawaban_{{ $x }}"
                            name="jawaban[{{ $x }}]"
                            value="{{ old("jawaban.$x", $item->jawabans->isNotEmpty() ? $item->jawabans->first()->jawaban : '') }}"
                            placeholder="Jawaban"
                            {{ (!isset($action))?'disabled':'' }}/>
                        @error("jawaban.$x")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @php $x++; @endphp
            @endforeach
            @can('Survei RW Jawaban Create')
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            @endcan
        </form>
    </div>
</div>
@endsection

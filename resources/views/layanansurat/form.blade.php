@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    @if (isset($surat))
        Edit Surat {{ $surat->pembuat->name }} - Digitalisasi Desa
    @else
        Buat Surat - Digitalisasi Desa
    @endif
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('layanansurat.index') }}">Surat</a>
            </li>
            @if (isset($surat))
                <li class="breadcrumb-item active">Edit Surat {{ $surat->pembuat->name }}</li>
            @else
                <li class="breadcrumb-item active">Buat Surat</li>
            @endif
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Buat Surat</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ $action }}" id="form-area" enctype="multipart/form-data">
                    @isset($surat) @method('PUT') @endisset
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="surat">Surat <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select
                              id="surat"
                              name="surat"
                              class="form-select form-select-lg @error('surat') is-invalid @enderror"
                              data-allow-clear="true"
                              onchange="suratTujuan()">
                                <option value="">Pilih Surat</option>
                                @foreach ($list_surat as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ ((!is_null(old('surat')) && old('surat') == $item->id) ? 'selected' : '') }}
                                        >{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('surat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="surat_tujuan_id">Tujuan <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <select
                              id="surat_tujuan_id"
                              name="surat_tujuan_id"
                              class="form-select form-select-lg @error('surat_tujuan_id') is-invalid @enderror"
                              data-allow-clear="true">
                            </select>
                            @error('surat_tujuan_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        function suratTujuan()
        {
            const select2 = $('#surat');
            $.ajax({
                url: "{{ route('layanansurat.tujuan') }}",
                type: "POST",
                data: {
                    surat:select2.val(),
                    '_token':'{{ csrf_token() }}'
                },
                beforeSend:function(){
                    $('#surat_tujuan_id').empty();
                },
                success: function(response) {
                    let html = "<option value=''>Pilih Tujuan</option>";
                    response.forEach(element => {
                        html += "<option value='"+element.id+"'>"+element.nama+"</option>"
                    });
                    $('#surat_tujuan_id').append(html);
                },
                error: function(xhr, status, error) {
                }
            });
        }
        $(document).ready(function(){
            const selectSurat = $('#surat');
            const selectTujuan = $('#surat_tujuan_id');
            select2Focus(selectSurat);
            select2Focus(selectTujuan);
            selectSurat.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Pilih Surat',
                dropdownParent: selectSurat.parent()
            });
            selectTujuan.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Pilih Tujuan',
                dropdownParent: selectTujuan.parent()
            });
        })

    </script>
@endsection

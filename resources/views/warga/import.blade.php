@extends('layouts.backend.main')

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('title')
    Import Warga - Digitalisasi Desa
@endsection

@section('content')

<div class="d-flex justify-content-end">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('datawarga.index') }}">Data Warga</a>
            </li>
            <li class="breadcrumb-item active">Import Warga</li>
        </ol>
    </nav>
</div>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div id="alert-show" style="display: none">
    <div class="alert alert-primary" id="pesan-text">
    </div>
</div>
<div class="row">
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Import Warga</h5>
              <small class="text-danger float-end">* wajib diisi</small>
            </div>
            <div class="card-body">
                <div id="progress-container" style="display: none;">
                    <div class="p-2">
                        <div class="progress bg-label-primary" style="height: 12px;">
                            <div
                              class="progress-bar bg-primary"
                              role="progressbar"
                              id="progress-bar"
                              {{-- style="width: 20%"
                              aria-valuenow="20" --}}
                              aria-valuemin="0"
                              aria-valuemax="100"
                            ></div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="import-form">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label" for="file">File Excel <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input
                                type="file"
                                class="form-control @error('file') is-invalid @enderror"
                                id="file"
                                name="file"
                                placeholder="Masukkan File Excel" />
                            @error('file')
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

@section('pagejs')
    <script>
        function calculatePercentage(totalRows, filledRows) {
            if (totalRows === 0) return 0; // Menghindari pembagian dengan nol
            return (filledRows / totalRows) * 100;
        }

        function showProgress(link){
            let linkIndex = '{{ route("datawarga.index") }}';
            let progressBar = $('#progress-bar');
            let pesanText = $('#pesan-text');

            interval = setInterval(() => {
                $.ajax({
                    url     : "{{ route('datawarga.import.status') }}",
                    method  : "GET",
                    success : function(data){
                        progressBar.css('width', calculatePercentage(data.total, data.processed) + '%').text(calculatePercentage(data.total, data.processed) + '%');
                        if(data.total == data.processed){
                            clearInterval(interval);
                            pesanText.html('Import Data Selesai. <a href="'+linkIndex+'">Klik Disini</a> untuk kembali ke halaman awal');
                        }
                    }
                })
            }, 3000);
        }
        $(document).ready(function() {
            $('#import-form').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let progressContainer = $('#progress-container');
                let pesanContainter = $('#alert-show');
                let progressBar = $('#progress-bar');
                let pesanText = $('#pesan-text');
                let interval;

                $.ajax({
                    url: this.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    beforeSend: function() {
                        progressContainer.show();
                        pesanContainter.show();
                    },
                    success: function(data){
                        console.log(data);
                        pesanText.text(data.status);
                        if(data.success){
                            showProgress();
                        }
                    },
                    error:function(err){
                        console.log(err);
                        pesanText.text('Internal Server Error');
                    }
                })
            })
        })
    </script>
@endsection

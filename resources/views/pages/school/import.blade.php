@extends('layout.master-page')

@section('title', $title)

@section('content')

    <div class="col-lg-6">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-4 text-primary font-weight-bold">{{ $title }}</h1>
            <div>
                <a href="{{ route('schools.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm mr-2">Kembali</a>
            </div>
        </div>
        {{-- End Header --}}

        {{-- Content --}}
        <div class="card">
            <div class="card-body">
                {{-- <div class="alert alert-info">
                    <a href="{{ asset('excel_import_template/students_import.xlsx') }}">Download</a> Format Import Excel
                    Siswa
                </div> --}}
                <form action="{{ route('schools.importAllByExcel') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="excel_file">Unggah File Excel</label>
                        <div class="custom-file">
                            <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                class="custom-file-input @error('excel_file') is-invalid @enderror" name="excel_file"
                                id="excel_file">
                            <label class="custom-file-label" for="excel_file" data-browse="Pilih Berkas">Unggah
                                Berkas...</label>
                        </div>

                        @error('excel_file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
        {{-- End Content --}}

    </div>

    @push('js')
        <script>
            document.querySelector('#excel_file').addEventListener('change', function(e) {
                var file = document.getElementById("excel_file").files[0];
                e.target.nextElementSibling.innerText = file.name
            })
        </script>
    @endpush

@endsection

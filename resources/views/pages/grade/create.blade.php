@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('grade.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('grade.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="grade-input">Tingkat<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('grade_name') is-invalid @enderror"
                                name="grade_name" id="grade-input" placeholder="1, 2, 3, dsb"
                                value="{{ old('grade_name') }}" autocomplete="off" autofocus>
                            @error('grade_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table academy years --}}
    </div>
    {{-- END ROW --}}
@endsection

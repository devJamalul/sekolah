@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table tuitions type --}}
        <div class="col-lg-6">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('tuition-type.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>


            @error('school_id')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('tuition-type.update', ['tuition_type' => $tuitionType->id]) }}" method="post">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                        <div class="form-group">
                            <label for="year-academy-input">Tipe Biaya <span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control  @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $tuitionType->name) }}" id="year-academy-input">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-check my-2">
                            <input type="checkbox" class="form-check-input" name="generatable" value="1"
                                @checked(old('generatable', $tuitionType->recurring) == '1') id="generatable-checkbox">
                            <label class="form-check-label" for="generatable-checkbox">Rutin</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table tuitions type --}}
    </div>
    {{-- END ROW --}}
@endsection

@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('classroom.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('classroom.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="grade-select">Tahun Ajaran<span class="text-small text-danger">*</span></label>
                            <select class="form-control @error('academic_year_id') is-invalid @enderror"
                                name="academic_year_id">
                                <option value="">-</option>
                                @foreach ($academicYears as $item)
                                    <option value="{{ $item->id }}" @selected(old('academic_year_id')==$item->id)>
                                        {{ $item->academic_year_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="grade-select">Tingkat<span class="text-small text-danger">*</span></label>
                            <select class="form-control @error('grade_id') is-invalid @enderror" name="grade_id"
                                id="grade-select">
                                <option value="">-</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" @selected(old('grade_id')==$grade->id)>
                                        {{ $grade->grade_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grade_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="year-academy-input">Kelas<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                id="year-academy-input" value="{{old('name')}}">
                            @error('name')
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

@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('tuition.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('tuition.update', ['tuition' => $tuition->id]) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="tuition-type-select">Tipe Biaya</label>
                            <select class="form-control  @error('tuition_type_id') is-invalid @enderror" name="tuition_type_id"
                                id="tuition-type-select">
                                <option value="">-</option>
                                @foreach ($tuitionTypes as $tuitionType)
                                    <option value="{{ $tuitionType->id }}" @if ($tuition->tuition_type_id === $tuitionType->id) selected @endif>
                                        {{ $tuitionType->name }}
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
                            <label for="academic-year-select">Tahun Akademik</label>
                            <select class="form-control  @error('academic_year_id') is-invalid @enderror" name="academic_year_id"
                                id="academic-year-select">
                                <option value="">-</option>
                                @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}" @if ($tuition->academic_year_id === $academicYear->id) selected @endif>
                                        {{ $academicYear->academic_year_name }}
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
                            <label for="grade-select">Tingkat</label>
                            <select class="form-control  @error('grade_id') is-invalid @enderror" name="grade_id"
                                id="grade-select">
                                <option value="">-</option>
                                @foreach ($grades as $rade)
                                    <option value="{{ $grade->id }}" @if ($tuition->grade_id === $grade->id) selected @endif>
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
                            <label for="price-input">Nominal</label>
                            <input type="text" class="form-control  @error('price') is-invalid @enderror" name="price"
                                value="{{ $tuition->price }}" id="price-input" placeholder="" pattern="[0-9]+">
                            @error('period')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="grade-select">Permintaan dari</label>
                            <select class="form-control select2 @error('requested_by') is-invalid @enderror" name="requested_by"
                                id="request-by-select">
                                <option value="">-</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($tuition->request_by === $user->id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('requested_by')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="approved-by-select">Tingkat</label>
                            <select class="form-control  @error('approved_by') is-invalid @enderror" name="approved_by"
                                id="approved-by-select">
                                <option value="">-</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($tuition->approval_by === $user->id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approved_by')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table academy years --}}
    </div>
    {{-- END ROW --}}
@endsection

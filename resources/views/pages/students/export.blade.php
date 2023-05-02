@extends('layout.master-page')

@section('title', $title)

@section('content')

  <div class="col-lg-6">

    {{-- Header --}}
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>
        <div>
            <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm mr-2">Kembali</a>
        </div>
    </div>
    {{-- End Header --}}

    {{-- Content --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('students.exportStudentReport') }}" method="post">
              @csrf

              {{-- Academic Year Select --}}
              <div class="col">
                <div class="form-group">
                  <label for="academic_year">Berdasarkan Tahun Akademik</label>
                  <select id="academic_year" name="academic_year" class="select2 form-control">
                    <option value="" selected>--- Pilih ---</option>
                    @foreach ($academic_years as $academic_year)
                      <option value="{{ $academic_year->id }}">{{ $academic_year->academic_year_name }}</option>
                    @endforeach
                  </select>
                  @error('academic_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              {{-- End Academic Year Select --}}

              {{-- Grade Select --}}
              <div class="col">
                <div class="form-group">
                  <label for="grade">Berdasarkan Tingkatan</label>
                  <select id="grade" name="grade" class="select2 form-control">
                    <option value="" selected>--- Pilih ---</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                  @error('grade')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              {{-- End Grade Select --}}

              {{-- Classroom Select --}}
              <div class="col">
                <div class="form-group">
                  <label for="classroom">Berdasarkan Ruang Kelas</label>
                  <select id="classroom" name="classroom" class="select2 form-control">
                    <option value="" selected>--- Pilih ---</option>
                    @foreach ($classrooms as $classroom)
                      <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endforeach
                  </select>
                  @error('classroom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              {{-- End Classroom Select --}}

              <div>
                <button type="submit" class="btn btn-primary">Ekspor Excel</button>
                <button type="submit" class="btn btn-primary">Ekspor PDF</button>
              </div>

            </form>
        </div>
    </div>
    {{-- End Content --}}

  </div>

  @push('js')
  <script>
    // var academicYearSection = $('#select_academic_year_section')
    // var gradeSection = $('#select_grade_section')

    // $('#academic_year').on('select2:select', function (e) {
    //     var data = e.params.data;
    //     console.log(e.params.data.id != "");
    //     if (e.params.data.id) {
    //       gradeSection.removeClass('d-none')
    //     } else {
    //       gradeSection.addClass('d-none')
    //     }
    // });
  </script>
  @endpush
    
@endsection
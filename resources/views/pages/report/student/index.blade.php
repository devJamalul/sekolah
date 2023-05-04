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
            <form action="{{ route('reports.students.export') }}" method="post">
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
              <div id="classroomSection" class="col d-none">
                <div class="form-group">
                  <label for="classroom">Berdasarkan Ruang Kelas</label>
                  <select id="classroom" name="classroom" class="select2 form-control"></select>
                  @error('classroom')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              {{-- End Classroom Select --}}

              <div>
                <button type="submit" name="action" value="excel" class="btn btn-primary">Ekspor Excel</button>
                <button type="submit" name="action" value="pdf" class="btn btn-primary">Ekspor PDF</button>
              </div>

            </form>
        </div>
    </div>
    {{-- End Content --}}

  </div>

  @push('js')
  <script>
    const classroomSection = $('#classroomSection')

    var academicYearValue = ""
    var gradeValue = ""
    var classroomValue = ""

    $('#academic_year').on('select2:select', function (e) {
        academicYearValue = e.params.data.id;
        getClassroomData()
    });

    $('#grade').on('select2:select', function (e) {
        gradeValue = e.params.data.id;
        getClassroomData()
    });

    async function getClassroomData(){
      if (academicYearValue != "" || gradeValue != "") classroomSection.removeClass('d-none') // Show Classroom Input Section
      if (academicYearValue == "" && gradeValue == "") classroomSection.addClass('d-none') // Hide Classroom Input Section

      const getData = await fetch(route('reports.students.getClassroomByFilter'), {
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          "data": 'asdasd',
          academic_year: academicYearValue,
          grade: gradeValue,
          classroom: classroomValue,
        })
      })
      const response = await getData.json()

      // Remove all Classroom Options
      $('#classroom')
        .empty()
      // End Remove all Classroom Options

      // Populate Select Option 
      if (response?.classrooms?.length > 0) {
        $('#classroom').append(`<option value="" selected>--- Pilih ---</option>`)

        response.classrooms.forEach(classroom => {
          $('#classroom')
            .append($("<option></option>")
              .attr("value", classroom.id)
              .text(classroom.name));
          });
      } else {
        $('#classroom')
          .append(`<option value="" selected>Tidak ada data</option>`)
      }
      // End Populate Select Option 
      
    }
  </script>
  @endpush
    
@endsection
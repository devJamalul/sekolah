@extends('layout.master-page')

@section('title', $title)

@section('content')

	<div class="col-lg-12">

			{{-- Header --}}
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('transactions.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Kembali</a>
      </div>
			{{-- End Header --}}

      {{-- Student Data --}}
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header bg-primary text-white font-weight-bold">
              Biodata Siswa
            </div>
            <div class="card-body">
              <div class="row">
                <table class="table col-6">
                  <tbody>
                      <tr>
                          <td scope="row">Nama Siswa</td>
                          <td class="text-primary font-weight-bold">{{ $student->name }} ({{ $student->gender }})</td>
                      </tr>
                      <tr>
                          <td scope="row">Tanggal Lahir</td>
                          <td class="text-primary font-weight-bold">{{ $student->dob->format('d F Y') }}</td>
                      </tr>
					  <tr>
                          <td scope="row">Email</td>
                          <td class="text-primary font-weight-bold">{{ $student->email }}</td>
					  </tr>
                      <tr>
                          <td scope="row">NIS</td>
                          <td class="text-primary font-weight-bold">{{ $student->nis }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Kelas</td>
                          <td class="text-primary font-weight-bold">{{ $student?->classrooms()->latest()->first()?->grade->grade_name . " " . $student?->classrooms()->latest()->first()?->name }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Alamat</td>
                          <td class="text-primary font-weight-bold">{{ $student->address }}</td>
                      </tr>
                  </tbody>
                </table>
                <table class="table col-6">
                  <tbody>
                      <tr>
                          <td scope="row">Nama Ayah</td>
                          <td class="text-primary font-weight-bold">{{ $student->father_name ?? '-' }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Nama Ibu</td>
                          <td class="text-primary font-weight-bold">{{ $student->mother_name ?? '-' }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Nama Wali</td>
                          <td class="text-primary font-weight-bold">{{ $student->guardian_name ?? '-' }}</td>
                      </tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- End Student Data --}}

			{{-- Student Tuitions --}}
      <div class="row mt-3">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header font-weight-bold d-sm-flex align-items-center justify-content-between">
							<span>Biaya Siswa</span>
							<div class="d-flex align-items-center" style="width: 40%; height: 100%;">
								<label for="selectAcademicYears" class="mt-2" style="width: 100%">Tahun Akademik:</label>
								<select name="" id="selectAcademicYears" class="select2">
									<option value="1" selected> Test</option>
									<option value="2"> Test 2</option>
									<option value="3"> 2002 - 2030</option>
								</select>
							</div>
            </div>
            <div class="card-body">
              <div class="row">
								<x-datatable
                    :tableId="'student-tuitions'" 
                    :tableHeaders="['NIK', 'Nama', 'Jenis Kelamin', 'Alamat', 'Tanggal lahir', 'Action']" 
                    :tableColumns="[
                        ['data' => 'nik'], 
                        ['data' => 'name'], 
                        ['data' => 'gender'],
                        ['data' => 'address'],
                        ['data' => 'dob'],
                        ['data' => 'action']
                    ]" 
                    :getDataUrl="route('datatable.students')" 
                />
              </div>
            </div>
          </div>
        </div>
      </div>
			{{-- End Student Tuitions --}}

    </div>
    {{-- END table schools --}}

		@push('js')
		<script>
				$(selectAcademicYears).on('change', () => location.reload())
				console.log($(selectAcademicYears).val());
			</script>
		@endpush
    
@endsection
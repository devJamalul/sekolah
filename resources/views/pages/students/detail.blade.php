@extends('layout.master-page')

@section('title', $title)

@section('content')

    {{-- start table schools --}}
    .
    <div class="col-lg-12">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('transactions.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Kembali</a>
      </div>

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
                          <td scope="row">NIS</td>
                          <td class="text-primary font-weight-bold">{{ $student->nis }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Kelas</td>
                          <td class="text-primary font-weight-bold">{{ $student?->classrooms()->latest()->first()?->grade->grade_name . " " . $student?->classrooms()->latest()->first()?->name }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Orang Tua</td>
                          <td class="text-primary font-weight-bold">
                              {{ $student->father_name }} <br />
                              {{ $student->mother_name }} <br />
                              {{ $student->guardian_name }} <br />
                          </td>
                      </tr>
                      <tr>
                          <td scope="row">Alamat</td>
                          <td class="text-primary font-weight-bold">{{ $student->address }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Tanggal Lahir</td>
                          <td class="text-primary font-weight-bold">{{ $student->dob->format('d F Y') }}</td>
                      </tr>
                  </tbody>
                </table>
                <table class="table col-6">
                  <tbody>
                      <tr>
                          <td scope="row">Nama Siswa</td>
                          <td class="text-primary font-weight-bold">{{ $student->name }} ({{ $student->gender }})</td>
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
                          <td scope="row">Orang Tua</td>
                          <td class="text-primary font-weight-bold">
                              {{ $student->father_name }} <br />
                              {{ $student->mother_name }} <br />
                              {{ $student->guardian_name }} <br />
                          </td>
                      </tr>
                      <tr>
                          <td scope="row">Alamat</td>
                          <td class="text-primary font-weight-bold">{{ $student->address }}</td>
                      </tr>
                      <tr>
                          <td scope="row">Tanggal Lahir</td>
                          <td class="text-primary font-weight-bold">{{ $student->dob->format('d F Y') }}</td>
                      </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    {{-- END table schools --}}
    
@endsection
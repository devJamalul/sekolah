@extends('layout.master-page')

@section('title', $title)

@section('content')

    <div class="col-lg-12">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            <a href="{{ route('students.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">Kembali</a>
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
                                        <td class="text-primary font-weight-bold">{{ $student->name }}
                                            ({{ $student->gender }})</td>
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
                                        <td class="text-primary font-weight-bold">
                                            {{ $student?->classrooms()->latest()->first()?->grade->grade_name .' ' .$student?->classrooms()->latest()->first()?->name ??'Belum mendapat kelas' }}
                                        </td>
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
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
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
                                <option value="">--- Pilih ---</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->getKey() }}" @selected($selected_academic_year == $academic_year->getKey())>
                                        {{ $academic_year->academic_year_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Biaya</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($student_tuitions as $key => $student_tuition)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $student_tuition->note }}
                                                    {{ $student_tuition->period->format('F Y') }} </td>
                                                <td>{{ 'Rp. ' . number_format($student_tuition->grand_total, 0, ',', '.') }}
                                                </td>
                                                <td><span
                                                        class="text-capitalize badge {{$student_tuition->status == 'paid' ? 'badge-success' : 'badge-danger' }} text-white">{{ $student_tuition->status == 'pending' ? 'Belum lunas' : 'Lunas' }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('transactions.show', $student_tuition->student_id) }}"
                                                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Bayar</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" align="center">Tidak memiliki Bayaran</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
            $(selectAcademicYears).on('change', (event) => {
                window.location.href = `${window.location.pathname}?academic_year=${event.target.value}`
            })
        </script>
    @endpush

@endsection

@extends('layout.master-page')

@push('css')
    <style>
        #students tbody tr.selected {
            background-color: #4e72dfbd;
            color: white;
        }

        #students-classroom tbody tr.selected {
            background-color: #e7493bc5;
            color: #fff;
        }
    </style>
@endpush


@section('content')
    <div class="row">

        {{-- START MENU FILTER CLASSROOM --}}
        <div class="col-lg-12">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
            {{-- START VALIDATION ID STUDENTS --}}
            @error('id.*')
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="text-bold">siswa yang dipilih sudah tersedia : </span>
                    <ul>
                        @foreach ($errors->get('id.*') as $key => $msgs)
                            @foreach ($msgs as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
                @endif
                {{-- END VALIDATION ID STUDENTS --}}



                {{-- START SELECT CLASS  --}}
                <div class="d-flex justify-content-between ">
                    <div class="w-75 d-flex py-4">
                        <h6 class="font-weight-bold">Tahun Ajaran : {{ $academy_year?->academic_year_name }}</h6>
                    </div>
                    {{--  START SELECT CLASS COMPONENT TETAPKAN KELAS DAN HAPUS KELAS --}}
                    <div class="w-75 ml-5  d-flex flex-column">
                        <div class="row d-flex  mt-4 justify-content-between">
                            <div class="col-8">

                                {{-- START FORM TETAPKAN KELAS --}}
                                <form action="{{ route('assign-classroom-student.store') }}" class="row" method="post">
                                    <div class="col-6">
                                        @csrf

                                        {{-- START SELECT CLASSROOM --}}
                                        <div class="form-group">
                                            <select
                                                class="form-control select2 @error('classroom_id')
                                                         is-invalid
                                                         @enderror"
                                                name="classroom_id" id="classroom_id">
                                                <option value="">Kelas</option>
                                                @foreach ($classroom as $key => $class)
                                                    <option value="{{ $class->id }}"
                                                        {{ session('classroom_id') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->grade->grade_name }} -
                                                        {{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('classroom_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        {{-- END SELECT CLASSROOM --}}
                                    </div>

                                    <div class="col-6">
                                        {{-- START BUTTON TETAPKAN KELAS --}}
                                        <div class="form-group ">
                                            <button type="submit" id="assign-classroom-store"
                                                class="btn btn-primary btn-block">
                                                <i class="fa fa-arrow-circle-right " aria-hidden="true"></i>
                                                {{ $title }}
                                            </button>
                                        </div>
                                        {{-- END BUTTON TETAPKAN KELAS  --}}
                                    </div>
                                </form>
                                {{-- END FORM TETAPKAN KELAS --}}

                            </div>
                            <div class="col-4">

                                {{-- START FORM HAPUS KELAS --}}
                                <form action="{{ route('assign-classroom-student.destroy') }}" class="form-group"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="classroom_id">
                                    <button type="submit" id="assign-classroom-delete" class="btn btn-danger btn-block ">
                                        <i class="fa fa-trash " aria-hidden="true"></i>
                                        <span>Hapus Siswa Terpilih</span>
                                    </button>
                                </form>
                                {{-- END FORM HAPUS KELAS --}}


                            </div>
                        </div>
                    </div>
                    {{--  START SELECT CLASS COMPONENT TETAPKAN KELAS DAN HAPUS KELAS --}}

                </div>
                {{-- END SELECT CLASS  --}}
            </div>
            {{-- START MENU FILTER CLASSROOM --}}

            {{-- START ASSIGN CLAASS --}}
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body ">

                        <div class="d-flex justify-content-between">
                            <div class="w-75 d-flex flex-column">
                                @php
                                    $tableColumns = [['data' => 'id'], ['data' => 'nis'], ['data' => 'name'], ['data' => 'dob']];
                                @endphp
                                <table class="table table-bordered" id="students" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Tanggal lahir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
                            <div class="p-2 d-flex flex-column justify-content-arround ">
                                <span>
                                    <i class="fa fa-arrow-circle-right text-primary fa-2x" style="margin-top:45px "
                                        aria-hidden="true"></i>
                                </span>

                            </div>
                            <div class="w-75  d-flex flex-column">

                                @php
                                    $tableColumns = [['data' => 'id'], ['data' => 'nis'], ['data' => 'name'], ['data' => 'dob']];
                                @endphp
                                <table class="table table-bordered" id="students-classroom" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Tanggal lahir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- END ASSIGN CLASS --}}
        </div>
    @endsection

    @push('css')
        <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endpush

    @push('js')
        <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('page/assign-classroom-student/index.js') }}"></script>
    @endpush

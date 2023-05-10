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
            <input type="hidden" id="session_classroom" value="{{ session('classroom_id') }}">
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
                <div class="d-flex justify-content-between " style="margin: -10px">
                    <div class="w-75 d-flex mt-4">
                        {{-- START SELECT ACADEMY YEARS --}}
                        <div class="form-group ml-2">
                            <select
                                class="form-control select2 @error('academy_year') is-invalid
                                             @enderror"
                                name="academy_year" id="academy_year">
                                @foreach ($academy_years as $key => $years)
                                    <option value="{{ $years->id }}"
                                        {{ session('academy_year') == $years->id ? 'selected' : '' }}>
                                        {{ $years->academic_year_name }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group ml-auto">
                            <button class="btn btn-primary btn-sm filter">
                                Filter
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                        {{-- END SELECT ACADEMY YEARS --}}
                    </div>
                    {{--  START SELECT CLASS COMPONENT TETAPKAN KELAS DAN HAPUS KELAS --}}
                    <div class="w-75 ml-5  d-flex flex-column">
                        <div class="row d-flex  mt-4 justify-content-between">
                            <div class="col-7">


                                {{-- START FORM TETAPKAN KELAS --}}
                                <form action="{{ route('assign-classroom-student.store') }}" class="row" method="post">
                                    <div class="col-5 ">
                                        @csrf

                                        {{-- START SELECT CLASSROOM --}}
                                        <div class="form-group">
                                            <select
                                                class="form-control select2 @error('classroom_id')
                                                         is-invalid
                                                         @enderror"
                                                name="classroom_id" id="classroom_id">
                                                <option value="">Kelas</option>

                                            </select>
                                            @error('classroom_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small>
                                                <b>Wali Kelas : <span id="staff-class"></span></b>
                                            </small>
                                        </div>
                                        {{-- END SELECT CLASSROOM --}}
                                    </div>

                                    <div class="col-6">
                                        {{-- START BUTTON TETAPKAN KELAS --}}
                                        <div class="form-group ">
                                            <button type="submit" id="assign-classroom-store"
                                                class="btn btn-primary btn-block btn-sm">
                                                <i class="fa fa-arrow-circle-right " aria-hidden="true"></i>
                                                {{ $title }}
                                            </button>
                                        </div>
                                        {{-- END BUTTON TETAPKAN KELAS  --}}
                                    </div>
                                </form>
                                {{-- END FORM TETAPKAN KELAS --}}

                            </div>
                            <div class="col-5">

                                {{-- START FORM HAPUS KELAS --}}

                                <input type="hidden" name="classroom_id">
                                <div class="btn-group btn-block">
                                    <button type="button" class="btn btn-sm btn-success btn-classroom-exist"
                                        onclick="assignclassroom('Naik kelas','{{ $academy_year->register?->id }}','{{ $academy_year->register?->academic_year_name }}')">
                                        <i class="fa fa-arrow-up" aria-hidden="true"></i>
                                        <span>Naik Kelas</span>
                                    </button>
                                    <button type="button" class="btn btn-sm  btn-danger btn-classroom-exist"
                                        onclick="assignclassroom('Pindah Kelas','{{ $academy_year->started?->id }}','{{ $academy_year->started?->academic_year_name }}')">
                                        <span class="fa-fw select-all fas"></span>

                                        <span>Pindah Kelas</span>
                                    </button>
                                </div>
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
                                        <tr id="filter-wrap" style="display: none">
                                            <th>

                                            </th>
                                            <th>
                                                <input type="text" class="form-control" placeholder="Cari NIS">
                                            </th>
                                            <th>
                                                <input type="text" class="form-control" placeholder="Cari Nama">
                                            </th>
                                            <th>
                                                <input type="text" class="form-control" placeholder="Cari Tanggal Lahir">
                                            </th>
                                        </tr>
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

        <div class="modal fade" id="assingclassroom-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="assingclassroom-modal-label"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('assign-classroom-student.destroy') }}" class="form-group" method="post">
                        <div class="modal-body">
                            @csrf
                            @method('DELETE')
                            <div class="form-group ">
                                <input type="hidden" name="classroom_old">
                                <input type="hidden" name="type">
                                <input type="hidden" id="academy-year-modal">
                            </div>
                            <div class="form-group">
                                <select
                                    class="form-control  select2 @error('classroom_id')
                                         is-invalid
                                         @enderror"
                                    name="classroom_id" id="list-classroom-modal">
                                    <option value="">Kelas</option>

                                </select>
                                @error('classroom_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('css')
        <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @endpush

    @push('js')
        <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('page/assign-classroom-student/index.js') }}"></script>
        <script>
            $(".filter").click(function() {
                $("#filter-wrap").toggle();
            })
        </script>
    @endpush

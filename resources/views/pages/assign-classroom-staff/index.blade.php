@extends('layout.master-page')




@section('content')
    <div class="row">
        {{-- START ASSIGN CLAASS --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body ">
                    <table class="table table-bordered" id="staffs-classroom" width="100%" cellspacing="0">
                        <thead>
                            {{-- <tr>
                                <th>
                                    <input type="text" class="form-control">
                                </th>
                                <th>
                                    <input type="text" class="form-control">
                                </th>
                                <th>
                                    <input type="text" class="form-control">
                                </th>
                                <th>
                                    <input type="text" class="form-control">
                                </th>
                                <th>
                                    <input type="text" class="form-control">
                                </th>
                                <th>
                                    <input type="text" class="form-control">
                                </th>
                                <th>
                                    Reset
                                </th>

                            </tr> --}}
                            <tr>
                                <th>NIK</th>
                                <th>NIP</th>
                                <th>Nama Staff/Guru</th>
                                <th>Jenis Kelamin</th>
                                <th>Wali Kelas</th>
                                <th>Tahun Ajaran</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- END ASSIGN CLASS --}}
    </div>

    <form action="" id="form-modal" method="post">
        <div class="modal" id="modalAssignClass">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="assingclassroom-modal-label"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="metode">
                        <input type="hidden" name="classroom_old" id="classroom_old">
                        <input type="hidden" name="id" id="staff_id_modal">
                        <input type="hidden" name="type" id="type">
                        <div class="form-group ">
                            <label for="">Tahun Ajaran <small class="text-danger">**</small> </label>
                            <select
                                class="form-control  @error('academy_year') is-invalid
                                         @enderror"
                                name="academy_year" id="academy_year_ubah" required>
                                <option value="">-- PILIH --</option>
                                @foreach ($academy_years as $key => $years)
                                    <option value="{{ $years->id }}">
                                        {{ $years->academic_year_name }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Kelas <small class="text-danger">**</small> </label>
                            <select
                                class="form-control  @error('classroom_id')
                                 is-invalid
                                 @enderror"
                                name="classroom_id" id="classroom-modal-ubah" required>
                                <option value="">-- PILIH --</option>

                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btn-modal">Ubah</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('page/assign-classroom-staff/index.js') }}"></script>
@endpush

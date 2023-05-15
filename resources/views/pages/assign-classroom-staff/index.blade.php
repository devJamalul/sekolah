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
                <div class="card-header p-0 bg-transparen border-0 row">
                    <div class="form-group col-3 mt-3">
                        <a href="{{ route('assign-classroom-staff.create') }}" type="submit" id="assign-classroom-store"
                            class="btn btn-primary btn-block">
                            {{ $title }}
                        </a>
                    </div>
                </div>
                <div class="card-body ">
                    <table class="table table-bordered" id="staffs-classroom" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Walikelas Dari</th>
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
@endsection

@push('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('page/assign-classroom-staff/index.js') }}"></script>
@endpush

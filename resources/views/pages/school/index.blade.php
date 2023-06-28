@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table schools --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                @can('schools.create')
                    <a href="{{ route('schools.create') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-capitalize">Tambah
                        {{ $title }}</a>
                @endcan
        
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="schools" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Nama Pimpinan</th>
                                <th>No Hp Pimpinan</th>
                                <th>Name PIC</th>
                                <th>Email PIC</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- END table schools --}}

    </div>
    {{-- END ROW --}}
@endsection


@push('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            const table = $("#schools")
            const url = route('datatable.schools');

            const columns = [{
                    data: 'school_name'
                },
                {
                    data: 'foundation_head_name'
                },
                {
                    data: 'foundation_head_tlpn'
                },
                {
                    data: 'pic_name'
                },
                {
                    data: 'pic_email'
                },
                {
                    data: 'action'
                }

            ];

            var reportTable = table.DataTable({
                paging: true,
                info: false,
                searching: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,

                },
                columns: columns,
            });


        });
    </script>
@endpush

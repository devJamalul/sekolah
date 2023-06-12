@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table Grade --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                <div>
                    @can('tuition-approval.store')
                        <a href="{{ route('publish-tuition.index') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Penerbitan Manual</a>
                    @endcan
                    @can('tuition.create')
                        <a href="{{ route('tuition.create') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah {{ $title }}</a>
                    @endcan
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'tuition'" :tableHeaders="[
                        'Tipe Uang Sekolah',
                        'Tahun Akademik',
                        'Tingkat',
                        'Nominal',
                        'Peminta',
                        'Status',
                        'Konfirmasi',
                        'Aksi',
                    ]" :tableColumns="[
                        ['data' => 'tuition_type', 'name' => 'price'],
                        ['data' => 'academic_year'],
                        ['data' => 'grade'],
                        ['data' => 'price'],
                        ['data' => 'request_by'],
                        ['data' => 'status'],
                        ['data' => 'approval_by'],
                        ['data' => 'action'],
                    ]" :getDataUrl="route('datatable.tuition')" />
                </div>
            </div>
        </div>
        {{-- END table Grade --}}
    </div>
    {{-- END ROW --}}
@endsection

@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-10">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('academy-year.create') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah {{ $title }}</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'academy-year'" :tableHeaders="['Tahun Akademik', 'Status Tahun Ajaran', 'Mulai Ajaran', 'Akhir Ajaran', 'Aksi']" :tableColumns="[
                        ['data' => 'academic_year_name'],
                        ['data' => 'status_years'],
                        ['data' => 'year_start'],
                        ['data' => 'year_end'],
                        ['data' => 'action'],
                    ]" :getDataUrl="route('datatable.academy-year')" />
                </div>
            </div>
        </div>
        {{-- END table academy years --}}
    </div>
    {{-- END ROW --}}
@endsection

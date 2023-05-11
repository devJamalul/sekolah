@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table Grade --}}
        <div class="col-lg-10">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                @can('classroom.create')
                    <a href="{{ route('classroom.create') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">TAMBAH</a>
                @endcan
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'classroom'" :tableHeaders="['Tahun Akademik', 'Tingkat', 'Kelas', 'Aksi']" :tableColumns="[
                        ['data' => 'academic_year.academic_year_name'],
                        ['data' => 'grade.grade_name'],
                        ['data' => 'name'],
                        ['data' => 'action'],
                    ]" :getDataUrl="route('datatable.classroom')" />
                </div>
            </div>
        </div>
        {{-- END table Grade --}}
    </div>
    {{-- END ROW --}}
@endsection

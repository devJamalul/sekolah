@extends('layout.master-page')

@section('content')

    {{-- start ROW --}}
    <div class="row">

        {{-- start table Approval --}}
        <div class="col-lg-10">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                <a href="{{ route('grade.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">TAMBAH</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'grade'" :tableHeaders="['Tingkat', 'Aksi']" :tableColumns="[['data' => 'grade_name'], ['data' => 'action']]" :getDataUrl="route('datatable.grade')" />
                </div>
            </div>
        </div>
        {{-- END table Approval --}}
        
    </div>
    {{-- END ROW --}}

@endsection

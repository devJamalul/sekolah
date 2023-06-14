@extends('layout.master-page')

@section('content')

    {{-- start ROW --}}
    <div class="row">

        {{-- start table Approval --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable
                        :tableId="'tuition_approval'"
                        :tableHeaders="['Uang Sekolah', 'Tingkatan', 'Nominal', 'Status', 'Aksi']"
                        :tableColumns="[
                            ['data' => 'tuition_name'],
                            ['data' => 'grade', 'name' => 'grade.grade_name'],
                            ['data' => 'price'],
                            ['data' => 'status'],
                            ['data' => 'action']
                        ]"
                        :getDataUrl="route('datatable.tuition-approval')"
                    />
                </div>
            </div>
        </div>
        {{-- END table Approval --}}

    </div>
    {{-- END ROW --}}

@endsection

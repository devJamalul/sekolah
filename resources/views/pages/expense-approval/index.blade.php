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
                        :tableId="'expense_approval'" 
                        :tableHeaders="['No Pengeluaran Biaya', 'Tanggal Pengeluaran', 'Nominal', 'Status', 'Aksi']" 
                        :tableColumns="[
                            ['data' => 'expense_number'],
                            ['data' => 'expense_date'],
                            ['data' => 'total'],
                            ['data' => 'status'],
                            ['data' => 'action'] 
                        ]" 
                        :getDataUrl="route('datatable.expense-approval')"
                    />
                </div>
            </div>
        </div>
        {{-- END table Approval --}}
        
    </div>
    {{-- END ROW --}}

@endsection

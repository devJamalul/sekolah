@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table Expense --}}
        <div class="col-lg-10">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                @can('expense.create')
                    <a href="{{ route('expense.create') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">TAMBAH</a>
                @endcan
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'expense'" :tableHeaders="['No Pengeluaran', 'Tanggal Pengeluaran', 'Diproses Sempoa', 'Status', 'Aksi']" :tableColumns="[
                        ['data' => 'expense_number'],
                        ['data' => 'expense_date'],
                        ['data' => 'is_sempoa_processed'],
                        ['data' => 'status'],
                        ['data' => 'action'],
                    ]" :getDataUrl="route('datatable.expense')" />
                </div>
            </div>
        </div>
        {{-- END table Expense --}}
    </div>
    {{-- END ROW --}}
@endsection

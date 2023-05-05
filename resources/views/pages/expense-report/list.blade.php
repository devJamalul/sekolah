@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table schools --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense-report.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'transaction-report'" :tableHeaders="[
                        'No Pengeluaran',
                        'Tanggal',
                        'Grand Total'
                    ]" :tableColumns="[
                        ['data' => 'expense_number'],
                        ['data' => 'expense_date'],
                        ['data' => 'total'],
                    ]" :getDataUrl="route('datatable.expense-report')" />
                </div>
            </div>
        </div>
        {{-- END table schools --}}

    </div>
    {{-- END ROW --}}
@endsection

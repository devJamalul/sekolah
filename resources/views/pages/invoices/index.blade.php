@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table invoices --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                @can('invoices.create')
                    <a href="{{ route('invoices.create') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah {{ $title }}</a>
                @endcan
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'invoices'" :tableHeaders="['Nomor Invoice', 'Tanggal Invoice', 'Jatuh Tempo', 'Total', 'Pembayaran', 'Status', 'Aksi']" :tableColumns="[
                        ['data' => 'invoice_number'],
                        ['data' => 'invoice_date'],
                        ['data' => 'due_date'],
                        ['data' => 'total_amount'],
                        ['data' => 'payment_status'],
                        ['data' => 'is_posted'],
                        ['data' => 'action'],
                    ]" :getDataUrl="route('datatable.invoices')" />
                </div>
            </div>
        </div>
        {{-- END table invoices --}}
    </div>
    {{-- END ROW --}}
@endsection

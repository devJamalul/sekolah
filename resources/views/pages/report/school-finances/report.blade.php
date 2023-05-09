@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <div class="btn-group">
                    <a href="{{ route('report-school-finances.export', $queryParameter) }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                        Export Excel
                    </a>
                    <a href="{{ route('report-school-finances.index') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                        Kembali
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-3">
                    <h6>
                        <b>Dana Dari</b> : {{ $wallet->name }}
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-3">
                    <h6> <b>Dana Awal</b> :
                        Rp. {{ number_format($wallet->init_value, 0, ',', '.') }} </h6>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-3">
                    <h6><b>Dana Akhir</b> : Rp. {{ number_format($wallet->last_balance, 0, ',', '.') }} </h6>

                </div>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'report-scholl-table'" :tableHeaders="['Dana', 'Tipe Uang', 'Note', 'tanggal']" :tableColumns="[
                        ['data' => 'amount'],
                        ['data' => 'cashflow_type'],
                        ['data' => 'note'],
                        ['data' => 'created_at'],
                    ]" :getDataUrl="route('datatable.report-school-finances', $queryParameter)" />
                </div>
            </div>
        </div>
    </div>

    {{-- END ROW --}}
@endsection

@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table wallet --}}
        <div class="col-lg-10">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                <a href="{{ route('wallet.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'wallet'"
                    :tableHeaders="['Catatan', 'Jumlah', 'Tipe', 'Tanggal']"
                    :tableColumns="[
                        ['data' => 'note'],
                        ['data' => 'amount'],
                        ['data' => 'cashflow_type'],
                        ['data' => 'created_at'],
                    ]"
                    :getDataUrl="route('datatable.wallet.logs', $wallet->id)" />
                </div>
            </div>
        </div>
        {{-- END table wallet --}}
    </div>
    {{-- END ROW --}}
@endsection

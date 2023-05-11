@extends('layout.master-page')

@section('content')

    {{-- start ROW --}}

    <div class="row">

        {{-- start table Grade --}}
        <div class="col-lg-10">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                <a href="{{ route('wallet.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">TAMBAH</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'grade'"
                    :tableHeaders="['Nama Dompet', 'Saldo Akhir', 'Aksi']"
                    :tableColumns="[
                        ['data' => 'name'],
                        ['data' => 'last_balance'],
                        ['data' => 'action']]"
                    :getDataUrl="route('datatable.wallet')" />
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    Keterangan
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <span class='text-small text-danger'>*</span> Dompet khusus Dana Bos
                    </p>
                </div>
            </div>
        </div>
        {{-- END table Grade --}}
    </div>
    {{-- END ROW --}}

@endsection

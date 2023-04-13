@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table schools --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('transaction-report.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Kembali</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'transaction-report'" :tableHeaders="[
                        'Nama',
                        'Kelas',
                        'Biaya',
                        'Nominal',
                        'Metode Pembayaran',
                        'Tanggal'
                    ]" :tableColumns="[
                        ['data' => 'name', 'name' => 'student_tuition.student.name'],
                        ['data' => 'class'],
                        ['data' => 'student_tuition', 'name' => 'student_tuition.note'],
                        ['data' => 'nominal'],
                        ['data' => 'payment_type'],
                        ['data' => 'tanggal'],
                    ]" :getDataUrl="route('datatable.transaction-report')" />
                </div>
            </div>
        </div>
        {{-- END table schools --}}

    </div>
    {{-- END ROW --}}
@endsection

@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table schools --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('schools.create') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah {{ $title }}</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <x-datatable :tableId="'schools'" :tableHeaders="[
                        'Nama',
                        'Provinsi',
                        'Kota',
                        'Alamat',
                        'Kode POS',
                        'Tingkatan',
                        'No Hp',
                        'Email',
                        'Name PIC',
                        'Email PIC',
                        'Aksi',
                    ]" :tableColumns="[
                        ['data' => 'school_name'],
                        ['data' => 'province'],
                        ['data' => 'city'],
                        ['data' => 'address'],
                        ['data' => 'postal_code'],
                        ['data' => 'grade'],
                        ['data' => 'phone'],
                        ['data' => 'email'],
                        ['data' => 'pic_name'],
                        ['data' => 'pic_email'],
                        ['data' => 'action'],
                    ]" :getDataUrl="route('datatable.schools')" />
                </div>
            </div>
        </div>
        {{-- END table schools --}}

    </div>
    {{-- END ROW --}}
@endsection

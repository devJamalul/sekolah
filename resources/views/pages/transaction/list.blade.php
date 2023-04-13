@extends('layout.master-page')

@section('content')
  {{-- start ROW --}}

  <div class="row">

    {{-- start table schools --}}
    <div class="col-lg-12">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('transactions.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Batal</a>
      </div>
      <div class="card">
        <div class="card-body">
          <x-datatable :tableId="'transactions'" :tableHeaders="[
                        'NIS',
                        'Nama Siswa',
                        'Kelas',
                        'Orang Tua',
                    ]" :tableColumns="[
                        ['data' => 'nis'],
                        ['data' => 'nama', 'name' => 'name'],
                        ['data' => 'kelas'],
                        ['data' => 'ortu'],
                    ]" :getDataUrl="route('datatable.transactions')" />
        </div>
      </div>
    </div>
    {{-- END table schools --}}

  </div>
  {{-- END ROW --}}
@endsection

@extends('layout.master-page')



@section('content')
  {{-- start ROW --}}

  <div class="row">

    {{-- start table tuituion type --}}
    <div class="col-lg-10">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('tuition-type.create') }}"
          class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah {{ $title }}</a>
      </div>
      <div class="card">
        <div class="card-body">
          <x-datatable :tableId="'tuition-type-table'" :tableHeaders="['Tipe Biaya', 'Rutin', 'Aksi']" :tableColumns="[['data' => 'name'], ['data' => 'recurring'], ['data' => 'action']]" :getDataUrl="route('datatable.tuition-type')" />
        </div>
      </div>
    </div>
    {{-- END table tuituion type --}}
  </div>
  {{-- END ROW --}}
@endsection

@extends('layout.master-page')

@section('content')
  {{-- start ROW --}}

  <div class="row">

    {{-- start table users --}}
    <div class="col-lg-10">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('users.create') }}"
          class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah {{ $title }}</a>
      </div>
      <div class="card">
        <div class="card-body">
          <x-datatable :tableId="'users'" :tableHeaders="['Nama', 'Email', 'Jabatan', 'Aksi']" :tableColumns="[['data' => 'name'], ['data' => 'email'], ['data' => 'jabatan'], ['data' => 'action']]" :getDataUrl="route('datatable.users')" />
        </div>
      </div>
    </div>
    {{-- END table users --}}

  </div>
  {{-- END ROW --}}
@endsection

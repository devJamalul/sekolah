@extends('layout.master-page')

@section('title', $title)

@section('content')

    {{-- start Datatable --}}
    <div class="col-lg-12">

        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>
            <div>
                <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm mr-2">Tambah</a>
                <a href="{{ route('students.import') }}" class="btn btn-success btn-sm">Impor Excel</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <x-datatable 
                    :tableId="'students'" 
                    :tableHeaders="['NIK', 'Nama', 'Jenis Kelamin', 'Alamat', 'Tanggal lahir', 'Action']" 
                    :tableColumns="[
                        ['data' => 'nik'], 
                        ['data' => 'name'], 
                        ['data' => 'gender'],
                        ['data' => 'address'],
                        ['data' => 'dob'],
                        ['data' => 'action']
                    ]" 
                    :getDataUrl="route('datatable.students')" 
                />
            </div>
        </div>
    </div>
    {{-- END Datatable --}}
    
@endsection
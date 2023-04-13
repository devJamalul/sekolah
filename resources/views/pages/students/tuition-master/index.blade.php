@extends('layout.master-page')

@section('title', $title)

@section('content')

    {{-- start Datatable --}}
    <div class="col-lg-10">

        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>
            <div>
                <a href="{{ route('tuition-master.create', ['id' => $id]) }}" class="btn btn-primary btn-sm mr-2">Tambah</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <x-datatable
                    :tableId="'students-tuition-master'" 
                    :tableHeaders="['Nama Biaya', 'Harga', 'Catatan', 'Aksi']" 
                    :tableColumns="[
                        ['data' => 'tuition_type', 'name' => 'tuition.tuition_type.name'],
                        ['data' => 'price'], 
                        ['data' => 'note'],
                        ['data' => 'action']
                    ]" 
                    :getDataUrl="route('datatable.students.tuition-master', ['id' => $id])" 
                />
            </div>
        </div>
    </div>
    {{-- END Datatable --}}
    
@endsection
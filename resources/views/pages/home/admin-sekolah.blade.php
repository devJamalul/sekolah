@extends('layout.master-page')

@section('title', 'Dashboard')

@section('content')
    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6">
            <div class=" mb-4">
                <div class="card border-left-{{ $total_staff_class }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $total_staff_class }} text-uppercase mb-1">
                                    {{ $total_staff }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $staff }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="{{ $staff_icon }} fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12">
            <div class="mb-4">
                <div class="card shadow h-100 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-{{ $total_students_class }} font-weight-bold text-uppercase mb-3">
                                    {{ $total_students }}
                                </div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tingkat</th>
                                        <th>Laki-laki</th>
                                        <th>Perempuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grades as $grade)
                                        <tr>
                                            <td>
                                                {{ $grade->grade_name }}
                                            </td>
    
                                            @php    
                                                $jumlahPria = 0;
                                                $jumlahPerempuan = 0;
                                            @endphp
    
                                            @foreach ($grade->classrooms()->whereHas('academic_year', function($q) {
                                                $q->active();
                                            })->get() as $item)
                                            
                                            @php
                                                foreach($item->students as $value){
    
                                                    if($value->gender == "L") {$jumlahPria++;}
                                                    else {$jumlahPerempuan++;}
                                                }
                                            @endphp
                                            @endforeach
                                            <td>{{ $jumlahPria }}</td>
                                            <td>{{ $jumlahPerempuan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Content Row -->
@endsection

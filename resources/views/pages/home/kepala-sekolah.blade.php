@extends('layout.master-page')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">{{ str(auth()->user()->getRoleNames()[0])->title }}</h1>

    <div class="row">
        <div class="col-md-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tingkatan</th>
                        <th class="text-center">Putra</th>
                        <th class="text-center">Putri</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        @php
                            $jumlah_putra = 0;
                            $jumlah_putri = 0;
                        @endphp
                        <tr>
                            <td scope="row">{{ $grade->grade_name }}</td>
                            @foreach ($grade->classrooms()->whereHas('academic_year', function ($query) {
                $query->active();
            })->get() as $classroom)
                                @foreach ($classroom->students as $student)
                                    @if ($student->gender == 'L')
                                        @php $jumlah_putra++; @endphp
                                    @else
                                        @php $jumlah_putri++; @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            <td class="text-center">{{ $jumlah_putra }}</td>
                            <td class="text-center">{{ $jumlah_putri }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table Publish Tuition --}}
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex">
                    <h6 class="mr-auto font-weight-bold text-primary">{{ $title }}</h6>
                    <a href="{{ route('tuition.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('publish-tuition.store') }}" method="post">
                        @csrf

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Biaya</th>
                                    <th>
                                        <button type="submit" class="btn btn-primary float-right btn-sm">Terbitkan</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tuitions as $tuition)
                                    <tr>
                                        <td>
                                            <label for="checkbox{{ $tuition->getKey() }}">
                                                {{ $tuition->tuition_type->name }}
                                                {{ $tuition->academic_year->academic_year_name }}
                                                - Tingkat {{ $tuition->grade->grade_name }}
                                            </label>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="tuitions[]" id="checkbox{{ $tuition->getKey() }}"
                                                value="{{ $tuition->getKey() }}">
                                                <label class="form-check-label" for="checkbox{{ $tuition->getKey() }}">
                                                    Pilih
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        {{-- END table Publish Tuition --}}
    </div>
    {{-- END ROW --}}
@endsection

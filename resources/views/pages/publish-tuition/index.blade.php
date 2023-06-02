@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
                <div>
                    @can('tuition.index')
                        <a href="{{ route('tuition.index') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">Kembali</a>
                    @endcan
                </div>
            </div>
            <div class="card">
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
                                                <input type="checkbox" name="tuitions[]"
                                                    id="checkbox{{ $tuition->getKey() }}"
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
    </div>
    {{-- END ROW --}}
@endsection

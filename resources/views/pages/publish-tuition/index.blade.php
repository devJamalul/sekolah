@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        <div class="col-lg-8">
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
                                    <th>Tahun Akademik</th>
                                    <th>Uang Sekolah</th>
                                    <th>Tingkatan</th>
                                    <th>
                                        <button type="submit" class="btn btn-primary float-right btn-sm">Terbitkan</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tuitions as $tuition)
                                    @php
                                        $tanda = '';
                                        if ($tuition->tuition_type->recurring) {
                                            $tanda = '<span class="text-danger">*</span>';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <label for="checkbox{{ $tuition->getKey() }}">
                                                {!! $tuition->academic_year->academic_year_name . $tanda !!}
                                            </label>
                                        </td>
                                        <td>
                                            <label for="checkbox{{ $tuition->getKey() }}">

                                                {!! $tuition->tuition_type->name . $tanda !!}
                                            </label>
                                        </td>
                                        <td>
                                            <label for="checkbox{{ $tuition->getKey() }}">
                                                {!! $tuition->grade->grade_name . $tanda !!}
                                            </label>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="tuitions[]"
                                                    id="checkbox{{ $tuition->getKey() }}" value="{{ $tuition->getKey() }}">
                                                <label class="form-check-label" for="checkbox{{ $tuition->getKey() }}">
                                                    Pilih{!! $tanda !!}
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

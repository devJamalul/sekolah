@extends('layout.master-page')

@section('title', $title)

@section('content')

    <div class="col-lg-12">

        {{-- Tuition Data --}}
        <div class="row">
            <div class="col-md-6">
                {{-- Header --}}
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                    <a href="{{ route('tuition-approval.index') }}"
                        class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">Kembali</a>
                </div>
                {{-- End Header --}}
                <div class="card card-body">
                    <div class="row">
                        <table class="table col-12">
                            <tbody>
                                <tr>
                                    <td scope="row">Nama Biaya</td>
                                    <td class="text-primary font-weight-bold">{{ $tuition->tuition_type->name }}</td>
                                </tr>
                                <tr>
                                    <td scope="row">Tingkatan</td>
                                    <td class="text-primary font-weight-bold">{{ $tuition->grade->grade_name }}</td>
                                </tr>
                                <tr>
                                    <td scope="row">Tahun Ajaran</td>
                                    <td class="text-primary font-weight-bold">
                                        {{ $tuition->academic_year->academic_year_name }}</td>
                                </tr>
                                <tr>
                                    <td scope="row">Nominal</td>
                                    <td class="text-primary font-weight-bold">IDR
                                        {{ number_format($tuition->price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td scope="row">Diajukan oleh</td>
                                    <td class="text-primary font-weight-bold">{{ $tuition->requested_by?->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row">Disetujui oleh</td>
                                    <td class="text-primary font-weight-bold">{{ $tuition->approved_by?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td scope="row">Status</td>
                                    <td class="text-primary font-weight-bold">
                                        @if ($tuition->status == 'rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @elseif ($tuition->status == 'approved')
                                            <span class="badge badge-success">Disetujui</span>
                                        @else
                                            <span class="badge badge-primary">Menunggu Persetujuan</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($tuition->reject_reason)
                                    <tr>
                                        <td scope="row">Alasan Penolakan</td>
                                        <td class="text-primary font-weight-bold">{{ $tuition->reject_reason}}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @can(['tuition-approval.update'])
                            @if ($tuition->status == \App\Models\Tuition::STATUS_PENDING )
                                <div style="width: 100%; display: flex; justify-content: end; ">
                                    <form
                                        action="{{ route('tuition-approval.update', ['tuition_approval' => $tuition->getKey()]) }}"
                                        method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group col-12">
                                            <textarea name="reject_reason" id="reject_reason" class="form-control @error('reject_reason') is-invalid @enderror" rows="5" placeholder="Alasan Penolakan (Opsional)"></textarea>
                                            @error('reject_reason')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-danger ml-2">Tolak</button>
                                        <button type="submit" name="action" value="approve"
                                            class="btn btn-primary ml-2">Setujui</button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        {{-- End Tuition Data --}}

    </div>
    {{-- END table schools --}}

@endsection

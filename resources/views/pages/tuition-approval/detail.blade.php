@extends('layout.master-page')

@section('title', $title)

@section('content')

	<div class="col-lg-12">

			{{-- Header --}}
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('tuition-approval.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Kembali</a>
      </div>
			{{-- End Header --}}

      {{-- Tuition Data --}}
      <div class="row">
        <div class="col-md-6">
          <div class="card card-body">
            <div class="row">
              <table class="table col-12">
                <tbody>
                    <tr>
                        <td scope="row">Nama Biaya</td>
                        <td class="text-primary font-weight-bold">{{ $tuition->tuition_type->name }}</td>
                    </tr>
                    <tr>
                        <td scope="row">Nominal</td>
                        <td class="text-primary font-weight-bold">IDR {{ number_format($tuition->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td scope="row">Diajukan oleh</td>
                        <td class="text-primary font-weight-bold">{{ $tuition->requested_by?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td scope="row">Disetujui oleh</td>
                        <td class="text-primary font-weight-bold">{{ $tuition->approved_by?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td scope="row">Status</td>
                        <td class="text-primary font-weight-bold">
                          @if ($tuition->deleted_at)
                            <span class="badge badge-danger">Ditolak</span>
                          @elseif ($tuition->approval_by)
                            <span class="badge badge-success">Disetujui</span>
                          @else
                            <span class="badge badge-primary">Menunggu Persetujuan</span>
                          @endif
                        </td>
                    </tr>
                </tbody>
              </table>

              @can(['tuition-approval.update'])
                @if (!$tuition->deleted_at && !$tuition->approval_by)
                  <div style="width: 100%; display: flex; justify-content: end; ">
                    <form action="{{ route('tuition-approval.update', ["tuition_approval" => $tuition->getKey()]) }}" method="post">
                      @csrf
                      @method('PUT')
                      <button type="submit" name="action" value="reject" class="btn btn-danger ml-2">Tolak</button>
                      <button type="submit" name="action" value="approve" class="btn btn-primary ml-2">Setujui</button>
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
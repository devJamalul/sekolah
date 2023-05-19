@extends('layout.master-page')

@section('title', $title)

@section('content')

    {{-- Tuition Data --}}
    <div class="row">
        <div class="col-md-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense-approval.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card card-body">
                <div class="row">
                    <table class="table col-12">
                        <tbody>
                            <tr>
                                <td scope="row">No Pengeluaran Biaya</td>
                                <td class="text-primary font-weight-bold">{{ $expense->expense_number }}</td>
                            </tr>
                            <tr>
                                <td scope="row">Tanggal Pengeluaran Biaya</td>
                                <td class="text-primary font-weight-bold">{{ $expense->expense_date }}</td>
                            </tr>
                            <tr>
                                <td scope="row">Nominal</td>
                                <td class="text-primary font-weight-bold">IDR
                                    {{ number_format($expense->expense_details()->sum(DB::raw('price * quantity')), 0, ', ', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Diajukan oleh</td>
                                <td class="text-primary font-weight-bold">{{ $expense->requested_by?->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Disetujui oleh</td>
                                <td class="text-primary font-weight-bold">{{ $expense->approved_by?->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Status</td>
                                <td class="text-primary font-weight-bold">
                                    @if ($expense->status == \App\Models\Expense::STATUS_REJECTED)
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif ($expense->status == \App\Models\Expense::STATUS_APPROVED)
                                        <span class="badge badge-success">Disetujui</span>
                                    @else
                                        <span class="badge badge-primary">Menunggu Persetujuan</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($expense->reject_reason)
                            <tr>
                                <td scope="row">Alasan Penolakan</td>
                                    <td class="text-primary font-weight-bold">{{ $expense->reject_reason}}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    @can(['expense-approval.update'])
                        @if ($expense->status == \App\Models\Expense::STATUS_PENDING)
                            <div class="col-12" style="width: 100%; display: flex; justify-content: end; ">
                                <form
                                    action="{{ route('expense-approval.update', ['expense_approval' => $expense->getKey()]) }}"
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
            {{-- End Tuition Data --}}
        </div>
    </div>
@endsection

@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table expense detail --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="expense-number-input">No Pengeluaran Biaya<span
                                        class="text-small text-danger">*</span></label>
                                <input type="text" class="form-control  @error('expense_number') is-invalid @enderror"
                                    name="expense_number" value="{{ $expense->expense_number }}" id="expense-number-input"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label for="expense-date-input">Tanggal Pengeluaran Biaya<span
                                        class="text-small text-danger">*</span></label>
                                <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                                    name="expense_date" id="expense-date-input"
                                    value="{{ $expense->expense_date->format('Y-m-d') }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="debit_account-input">Akun Pengeluaran Biaya<span
                                        class="text-small text-danger">*</span></label>
                                <input type="text" class="form-control @error('debit_account') is-invalid @enderror"
                                    name="debit_account" id="debit_account-input"
                                    value="{{ $expense->debit_account }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="note-input">Deskripsi</label>
                                <input type="text" class="form-control @error('note') is-invalid @enderror" name="note"
                                    id="note-input" value="{{ $expense->note }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="wallet_id-input">Sumber Biaya</label>
                                <input type="text" class="form-control @error('wallet_id') is-invalid @enderror" name="wallet_id"
                                    id="wallet_id-input" value="{{ $expense->wallet->name }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="price-input">Nominal</label>
                                <input type="text" class="form-control harga @error('price') is-invalid @enderror" name="price"
                                    id="price-input" value="{{ $expense->price }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="request_by-input">Peminta</label>
                                <input type="text" class="form-control @error('request_by') is-invalid @enderror" name="request_by"
                                    id="request_by-input" value="{{ $expense->requested_by->name }}" disabled>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="confirm_by-input">Konfirmasi</label>
                                        <input type="text" class="form-control @error('confirm_by') is-invalid @enderror" name="confirm_by"
                                            id="confirm_by-input" value="{{ $confirmation }}" disabled>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="status-input">Status</label>
                                            <br>
                                        @php
                                            echo match ($expense->status) {
                                                    'approved' => '<span class="badge badge-success">Disetujui</span>',
                                                    'pending' => '<span class="badge badge-dark">Pending</span>',
                                                    'rejected' => '<span class="badge badge-danger">Ditolak</span>',
                                                    'done' => '<span class="badge badge-success">Selesai</span>',
                                                    'outgoing' => '<span class="badge badge-info">Realisasi</span>',
                                                    default => '-'
                                                }
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            @if ($expense->approval_at)
                            <div class="form-group">
                                <label for="approval-date-input">Tanggal Konfirmasi<span
                                        class="text-small text-danger">*</span></label>
                                <input type="date" class="form-control @error('approval_date') is-invalid @enderror"
                                    name="approval_date" id="approval-date-input"
                                    value="{{ $expense->approval_at != null ? $expense->approval_at->format('Y-m-d') : $expense->rejected_at->format('Y-m-d') }}" disabled>
                            </div>
                            @endif
                            @if($expense->status == 'rejected')
                            <div class="form-group">
                                <label for="reject_reason-input">Alasan Penolakan</label>
                                <input type="text" class="form-control @error('reject_reason') is-invalid @enderror" name="reject_reason"
                                    id="reject_reason-input" value="{{ $expense->reject_reason }}" disabled>
                            </div>
                            @endif

                            @if ($expense->file_photo)
                                    <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                    Bukti Pengeluaran Biaya
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- END table expense detail --}}
    </div>
    {{-- END ROW --}}



  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Bukti Pengeluaran Biaya</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
            @if (in_array($fileExtension, $extensionType))
                <img src="{{ $expense->file_photo }}" alt="Bukti Pengeluaran Biaya" srcset="">
            @else
                <a class="btn btn-primary" href="{{ $expense->file_photo }}" rel="" target="_blank" download>Download</a>
            @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
    <script>
        formatAngka('#price-input')
        formatAngka('#quantity-input')
    </script>
@endpush

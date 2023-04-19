@extends('layout.master-page')


@section('content')

    {{-- start ROW --}}

    <div class="row">

        {{-- start table Expense --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expense.update', ['expense' => $expense->id]) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="expense-number-input">No Pengeluaran Biaya<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control  @error('expense_number') is-invalid @enderror" name="expense_number"
                                value="{{ $expense->expense_number }}" id="expense-number-input" placeholder="" disabled>
                            @error('expense_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="expense-date-input">Tanggal Pengeluaran Biaya<span class="text-small text-danger">*</span></label>
                            <input type="date" class="form-control @error('expense_date') is-invalid @enderror" name="expense_date"
                                id="expense-date-input" placeholder="" value="{{ $expense->expense_date }}">
                            @error('expense_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="note-input">Catatan</label>
                            <input type="text" class="form-control @error('note') is-invalid @enderror" name="note"
                                id="note-input" placeholder="" value="{{ $expense->note }}">
                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table Expense --}}
    </div>
    {{-- END ROW --}}

@endsection

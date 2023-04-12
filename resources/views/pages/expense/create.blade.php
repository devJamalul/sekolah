@extends('layout.master-page')


@section('content')

    {{-- start ROW --}}

    <div class="row">

        {{-- start table expense --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expense.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="expense-number-input">No Pengeluaran Biaya</label>
                            <input type="text" class="form-control @error('expense_number') is-invalid @enderror" name="expense_number"
                                id="expense-number-input" placeholder="" value="Exp/{{date('Y')}}/{{ str_pad($expenseNumber == 0 ? $expenseNumber += 1 : $expenseNumber += 1, 4, '0', STR_PAD_LEFT) }}">
                            @error('expense_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="expense-date-input">Tanggal Pengeluaran Biaya</label>
                            <input type="date" class="form-control @error('expense_date') is-invalid @enderror" name="expense_date"
                                id="expense-date-input" placeholder="">
                            @error('expense_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="note-input">Catatan</label>
                            <input type="text" class="form-control @error('note') is-invalid @enderror" name="note"
                                id="note-input" placeholder="">
                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="requested_by">Permintaan dari</label>
                            <select class="form-control select2 @error('requested_by') is-invalid @enderror" name="requested_by"
                                id="requested-by-select">
                                <option value="">-</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('requested_by')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="approved_by">Disetujui oleh</label>
                            <select class="form-control select2 @error('approved_by') is-invalid @enderror" name="approved_by"
                                id="approved-by-select">
                                <option value="">-</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approved_by')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> 
                        <button type="submit" class="btn btn-primary">Lanjutkan</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table expense --}}
    </div>
    {{-- END ROW --}}

@endsection

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
                    <form action="{{ route('expense.store') }}" method="post" id="expense">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="expense_date">Tanggal Pengeluaran Biaya<span
                                            class="text-small text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                                        name="expense_date" id="expense_date" aria-describedby="expense_date"
                                        value="{{ old('expense_date') }}" autocomplete="off" tabindex="1" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="expense_number">No. Pengeluaran Biaya<span
                                            class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('expense_number') is-invalid @enderror"
                                        name="expense_number" id="expense_number" autocomplete="off" tabindex="2"
                                        value="Exp/{{ date('Y') }}/{{ str_pad($expenseNumber == 0 ? ($expenseNumber += 1) : ($expenseNumber += 1), 4, '0', STR_PAD_LEFT) }}">
                                </div>
                                @if ($config)
                                <div class="form-group">
                                    <label for="debit_account">Akun Pengeluaran Biaya<span class="text-small text-danger">*</span></label>
                                    <select class="form-control select2" name="debit_account" id="debit_account" tabindex="3">
                                        <option value="">-</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account['kode'] }}" @selected(old('debit_account') == $account['kode'])>
                                                {{ $account['kode'] }} - {{ $account['akun'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="note">Deskripsi<span class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('note') is-invalid @enderror"
                                        name="note" id="note" aria-describedby="note" value="{{ old('note') }}"
                                        autocomplete="off" tabindex="4">
                                </div>
                                <div class="form-group">
                                    <label for="wallet_id">Sumber Biaya<span class="text-small text-danger">*</span></label>
                                    <select class="form-control" name="wallet_id" id="wallet_id" tabindex="5">
                                        <option value="">-</option>
                                        @foreach ($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" @selected(old('wallet_id') == $wallet->id)>
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="price">Nominal<span class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control harga @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ old('price') }}" tabindex="6">
                                </div>
                            </div>
                        </div>
                        <div class="btn-group float-right mt-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table expense --}}
    </div>
    {{-- END ROW --}}
@endsection

@push('js')
    <script>
        formatAngka('.harga')
    </script>
@endpush

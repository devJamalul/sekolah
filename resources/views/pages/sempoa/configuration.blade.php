@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table invoices --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
            </div>
            <div class="card">
                <div class="card-body">
                    @php
                        if ($config and !is_null($config->token)) {
                            $action = route('sempoa-configuration.update');
                        } else {
                            $action = route('sempoa-configuration.store');
                        }
                    @endphp
                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="token" class="col-sm-4 col-form-label">Token</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('token')is-invalid @enderror"
                                    id="token" name="token" value="{{ $config?->token }}"
                                    autocomplete="off">
                                @error('token')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if ($config and !is_null($config->token))
                            <hr />

                            <p class="text-primary font-weight-bold">Pembayaran Uang Sekolah</p>

                            <div class="form-group row">
                                <label for="tuition_debit_account" class="col-sm-4 col-form-label">Akun Debit</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        class="form-control @error('tuition_debit_account')is-invalid @enderror"
                                        id="tuition_debit_account" name="tuition_debit_account"
                                        value="{{ $config?->tuition_debit_account }}"
                                        autocomplete="off">
                                    @error('tuition_debit_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tuition_credit_account" class="col-sm-4 col-form-label">Akun Kredit</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        class="form-control @error('tuition_credit_account')is-invalid @enderror"
                                        id="tuition_credit_account" name="tuition_credit_account"
                                        value="{{ $config?->tuition_credit_account }}"
                                        autocomplete="off">
                                    @error('tuition_credit_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <hr />

                            <p class="text-primary font-weight-bold">Pengeluaran Biaya</p>

                            <div class="form-group row">
                                <label for="expense_debit_account" class="col-sm-4 col-form-label">Akun Debit</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        class="form-control @error('expense_debit_account')is-invalid @enderror"
                                        id="expense_debit_account" name="expense_debit_account"
                                        value="{{ $config?->expense_debit_account }}"
                                        autocomplete="off">
                                    @error('expense_debit_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="expense_credit_account" class="col-sm-4 col-form-label">Akun Kredit</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        class="form-control @error('expense_credit_account')is-invalid @enderror"
                                        id="expense_credit_account" name="expense_credit_account"
                                        value="{{ $config?->expense_credit_account }}"
                                        autocomplete="off">
                                    @error('expense_credit_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <hr />

                            <p class="text-primary font-weight-bold">Invoice</p>

                            <div class="form-group row">
                                <label for="invoice_debit_account" class="col-sm-4 col-form-label">Akun Debit</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        class="form-control @error('invoice_debit_account')is-invalid @enderror"
                                        id="invoice_debit_account" name="invoice_debit_account"
                                        value="{{ $config?->invoice_debit_account }}"
                                        autocomplete="off">
                                    @error('invoice_debit_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_credit_account" class="col-sm-4 col-form-label">Akun Kredit</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        class="form-control @error('invoice_credit_account')is-invalid @enderror"
                                        id="invoice_credit_account" name="invoice_credit_account"
                                        value="{{ $config?->invoice_credit_account }}"
                                        autocomplete="off">
                                    @error('invoice_credit_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary offset-sm-4">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        {{-- END table invoices --}}
    </div>
    {{-- END ROW --}}
@endsection

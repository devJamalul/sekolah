@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table invoices --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h6 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h6>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            @php
                                if ($config and !is_null($config->token)) {
                                    $action = route('sempoa-configuration.update');
                                    $token = 'readonly';
                                    $status = '';
                                    $notif = true;
                                } else {
                                    $action = route('sempoa-configuration.store');
                                    $token = '';
                                    $status = 'disabled';
                                    $notif = false;
                                }
                            @endphp
                            <form action="{{ $action }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group row">
                                    <label for="token" class="col-sm-4 col-form-label">Token</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control @error('token')is-invalid @enderror"
                                            id="token" name="token" value="{{ $config?->token }}" autocomplete="off"
                                            {{ $token }}>
                                        @error('token')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                @if ($config and !is_null($config->token))
                                    @php
                                        $status = '';
                                        if ($config->status != App\Models\SempoaConfiguration::STATUS_OPEN) {
                                            $status = 'disabled';
                                        }
                                    @endphp
                                    <hr />

                                    <p class="text-primary font-weight-bold">Pembayaran Uang Sekolah</p>

                                    <div class="form-group row">
                                        <label for="tuition_debit_account" class="col-sm-4 col-form-label">
                                            Default Akun Debit
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" name="tuition_debit_account"
                                                id="tuition_debit_account" {{ $status }}>
                                                <option value="">Pilih...</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account['kode'] }}" @selected($account['kode'] == $config?->tuition_debit_account)>
                                                        {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('tuition_debit_account')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tuition_credit_account" class="col-sm-4 col-form-label">
                                            Default Akun Kredit
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" name="tuition_credit_account"
                                                id="tuition_credit_account" {{ $status }}>
                                                <option value="">Pilih...</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account['kode'] }}" @selected($account['kode'] == $config?->tuition_credit_account)>
                                                        {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('tuition_credit_account')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr />

                                    <p class="text-primary font-weight-bold">Pengeluaran Biaya</p>

                                    <div class="form-group row">
                                        <label for="expense_debit_account" class="col-sm-4 col-form-label">
                                            Default Akun Debit
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" name="expense_debit_account"
                                                id="expense_debit_account" {{ $status }}>
                                                <option value="">Pilih...</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account['kode'] }}" @selected($account['kode'] == $config?->expense_debit_account)>
                                                        {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('expense_debit_account')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="expense_credit_account" class="col-sm-4 col-form-label">
                                            Default Akun Kredit
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" name="expense_credit_account"
                                                id="expense_credit_account" {{ $status }}>
                                                <option value="">Pilih...</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account['kode'] }}" @selected($account['kode'] == $config?->expense_credit_account)>
                                                        {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('expense_credit_account')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <hr />

                                    <p class="text-primary font-weight-bold">Invoice</p>

                                    <div class="form-group row">
                                        <label for="invoice_debit_account" class="col-sm-4 col-form-label">
                                            Default Akun Debit
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" name="invoice_debit_account"
                                                id="invoice_debit_account" {{ $status }}>
                                                <option value="">Pilih...</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account['kode'] }}" @selected($account['kode'] == $config?->invoice_debit_account)>
                                                        {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('invoice_debit_account')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="invoice_credit_account" class="col-sm-4 col-form-label">
                                            Default Akun Kredit
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" name="invoice_credit_account"
                                                id="invoice_credit_account" {{ $status }}>
                                                <option value="">Pilih...</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account['kode'] }}" @selected($account['kode'] == $config?->invoice_credit_account)>
                                                        {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('invoice_credit_account')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if ($status == '')
                                <button type="submit" class="btn btn-primary offset-sm-4"
                                    @if ($notif) onclick="return confirm('Pengaturan akan dikunci setelah ini. Apakah Anda sudah yakin?')" @endif>Simpan</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                @can('sempoa-configuration.destroy')
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-secondary text-light">
                                Configuration Permission
                            </div>
                            <div class="card-body">
                                <form action="{{ route('sempoa-configuration.destroy') }}" method="post">
                                    @csrf @method('PUT')
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status1"
                                            value="{{ App\Models\SempoaConfiguration::STATUS_OPEN }}" @checked($config?->status == App\Models\SempoaConfiguration::STATUS_OPEN)>
                                        <label class="form-check-label" for="status1">
                                            Open @if ($config?->status == App\Models\SempoaConfiguration::STATUS_OPEN) <em>(Current status)</em> @endif
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2"
                                            value="{{ App\Models\SempoaConfiguration::STATUS_LOCKED }}" @checked($config?->status == App\Models\SempoaConfiguration::STATUS_LOCKED)>
                                        <label class="form-check-label" for="status2">
                                            Lock @if ($config?->status == App\Models\SempoaConfiguration::STATUS_LOCKED) <em>(Current status)</em> @endif
                                        </label>
                                    </div>
                                    @if ($config)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status3"
                                            value="{{ App\Models\SempoaConfiguration::STATUS_RESET }}">
                                        <label class="form-check-label" for="status3">
                                            <span class="text-danger font-weight-bolder">Reset</span>
                                        </label>
                                    </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        {{-- END table invoices --}}
    </div>
    {{-- END ROW --}}
@endsection

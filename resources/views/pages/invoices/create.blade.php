@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('invoices.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" id="invoice">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Deskripsi<span class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('note') is-invalid @enderror"
                                        name="note" id="note" aria-describedby="note" value="{{ old('note') }}"
                                        autocomplete="off" tabindex="1" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="invoice_date">Tanggal Invoice<span
                                            class="text-small text-danger">*</span></label>
                                    <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                                        name="invoice_date" id="invoice_date" aria-describedby="invoice_date"
                                        value="{{ old('invoice_date') }}" autocomplete="off" tabindex="3">
                                </div>
                                @if ($config)
                                    <div class="form-group">
                                        <label for="credit_account">Akun Kredit Invoice<span
                                                class="text-small text-danger">*</span></label>
                                        <select class="form-control select2" name="credit_account" id="credit_account"
                                            tabindex="3">
                                            <option value="">-</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account['kode'] }}" @selected(old('credit_account') == $account['kode'])>
                                                    {{ $account['kode'] }} - {{ $account['akun'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_number">No. Invoice</label>
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                                        name="invoice_number" id="invoice_number" value="{{ old('invoice_number') }}"
                                        autocomplete="off" tabindex="2">
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Jatuh Tempo<span class="text-small text-danger">*</span></label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                        name="due_date" id="due_date" aria-describedby="due_date"
                                        value="{{ old('due_date') }}" autocomplete="off" tabindex="4">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-primary text-light">
                    Baris Invoice
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Barang<span class="text-small text-danger">*</span></th>
                                <th style="width: 30%">Harga<span class="text-small text-danger">*</span></th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                        name="item_name" id="item_name" value="{{ old('item_name') }}" autocomplete="off"
                                        form="invoice" tabindex="5">
                                </td>
                                <td>
                                    <input type="text" class="form-control harga @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ old('price') }}" autocomplete="off"
                                        form="invoice" tabindex="6">
                                    @error('price')
                                        {{ $message }}
                                    @enderror
                                </td>
                                <td>
                                    <button name="tambah" id="tambah" class="btn btn-primary btn-sm" type="submit"
                                        form="invoice">Tambah</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- END table academy years --}}
    </div>
    {{-- END ROW --}}
@endsection

@push('js')
    <script>
        formatAngka('.harga')
    </script>
@endpush

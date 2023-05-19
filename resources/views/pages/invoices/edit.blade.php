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
                    <form action="{{ route('invoices.update', $invoice->getKey()) }}" method="post" id="invoice">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Deskripsi<span class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('note') is-invalid @enderror"
                                        name="note" id="note" aria-describedby="note"
                                        value="{{ old('note', $invoice->note) }}" autocomplete="off" tabindex="1"
                                        autofocus>
                                    @error('note')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="invoice_date">Tanggal Invoice<span
                                            class="text-small text-danger">*</span></label>
                                    <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                                        name="invoice_date" id="invoice_date" aria-describedby="invoice_date"
                                        value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                                        autocomplete="off" tabindex="3">
                                    @error('invoice_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_number">No. Invoice<span
                                            class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror"
                                        name="invoice_number" id="invoice_number"
                                        value="{{ old('invoice_number', $invoice->invoice_number) }}" autocomplete="off"
                                        tabindex="2">
                                    @error('invoice_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Jatuh Tempo<span class="text-small text-danger">*</span></label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                        name="due_date" id="due_date" aria-describedby="due_date"
                                        value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                                        autocomplete="off" tabindex="4">
                                    @error('due_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="btn-group float-right mt-2">
                            <button type="submit" class="btn btn-primary ">Simpan</button>
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
                            <form action="{{ route('invoice-details.store', $invoice->getKey()) }}" method="post">
                                @csrf
                                <tr>
                                    <td>
                                        <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                            name="item_name" id="item_name" value="{{ old('item_name') }}"
                                            autocomplete="off" tabindex="5">
                                    </td>
                                    <td>
                                        <input type="text"
                                            class="form-control harga @error('price') is-invalid @enderror" name="price"
                                            id="price" value="{{ old('price') }}" autocomplete="off" tabindex="6">
                                    </td>
                                    <td>
                                        <button name="tambah" id="tambah" class="btn btn-primary btn-sm" type="submit">Tambah</button>
                                    </td>
                                </tr>
                            </form>
                            @php
                                $index = 7;
                            @endphp
                            @foreach ($invoice->invoice_details as $key => $item)
                            <input type="hidden" name="invoice_detail_id[{{ $key }}]" value="{{ $item->getKey() }}" form="invoice">
                                <tr>
                                    <td>
                                        <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                            name="item_name" id="item_name"
                                            value="{{ old('item_name', $item->item_name) }}" autocomplete="off"
                                            form="invoice" tabindex="{{ $index++ }}">
                                    </td>
                                    <td>
                                        <input type="text"
                                            class="form-control harga @error('price') is-invalid @enderror" name="price"
                                            id="price" value="{{ old('price', $item->price) }}" autocomplete="off"
                                            form="invoice" tabindex="{{ $index++ }}">
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('invoice-details.destroy', [
                                                'invoice' => $invoice->getKey(),
                                                'invoice_detail' => $item->getKey(),
                                            ]) }}"
                                            method="post">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="invoice_detail_id"
                                                value="{{ $item->getKey() }}">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <input type="hidden" name="array_max" value="{{ $key }}">
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

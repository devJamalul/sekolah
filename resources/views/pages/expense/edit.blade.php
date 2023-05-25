@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table expense --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expense.update', $expense->getKey()) }}" method="post" id="expense">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Deskripsi<span class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('note') is-invalid @enderror"
                                        name="note" id="note" aria-describedby="note" value="{{ old('note', $expense->note) }}"
                                        autocomplete="off" tabindex="1" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="expense_date">Tanggal Pengeluaran Biaya<span
                                            class="text-small text-danger">*</span></label>
                                    <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                                        name="expense_date" id="expense_date" aria-describedby="expense_date"
                                        value="{{ old('expense_date', $expense->expense_date) }}" autocomplete="off" tabindex="3">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expense_number">No. Pengeluaran Biaya</label>
                                    <input type="text" class="form-control @error('expense_number') is-invalid @enderror"
                                        name="expense_number" id="expense_number" 
                                        autocomplete="off" tabindex="2" value="{{ old('expense_number', $expense->expense_number) }}" >
                                        @error('expense_number')
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
                    Baris Pengeluaran Biaya
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Dompet<span class="text-small text-danger">*</span></th>
                                <th>Nama Barang<span class="text-small text-danger">*</span></th>
                                <th style="width: 15%">Kuantitas<span class="text-small text-danger">*</span></th>
                                <th style="width: 30%">Harga<span class="text-small text-danger">*</span></th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="{{ route('expense-detail.store', $expense->getKey()) }}" method="post">
                                @csrf
                                <tr>
                                    <td>
                                        <select class="form-control select2 @error('wallet_id') is-invalid @enderror" name="wallet_id"
                                        id="requested-by-select">
                                        <option value="">-</option>
                                        @foreach ($wallets as $wallet)
                                            <option value="{{ $wallet->id }}">
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('wallet_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                            name="item_name" id="item_name" value="{{ old('item_name') }}" autocomplete="off"
                                             tabindex="5">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control harga @error('quantity') is-invalid @enderror"
                                            name="quantity" id="quantity" value="{{ old('quantity') }}" autocomplete="off"
                                             tabindex="6" pattern="[0-9]+">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control harga @error('price') is-invalid @enderror"
                                            name="price" id="price" value="{{ old('price') }}" autocomplete="off"
                                             tabindex="6">
                                    </td>
                                    <td>
                                        <button name="tambah" id="tambah" class="btn btn-primary btn-sm" type="submit"
                                            >Tambah</button>
                                    </td>
                                </tr>
                            </form>
                            @php
                                $index = 7;
                            @endphp
                            @foreach ($expense->expense_details as $key => $item)
                                <input type="hidden" name="expense_detail_id[{{ $key }}]"
                                value="{{ $item->getKey() }}" form="expense">
                                <tr>
                                    <td>
                                        <select class="form-control select2 @error('array_wallet_id.'. $key) is-invalid @enderror" 
                                        name="array_wallet_id[{{ $key }}]"
                                        id="requested-by-select" form="expense">
                                        <option value="">-</option>
                                        @foreach ($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" @selected(old('array_wallet_id.'.$key, $item->wallet_id) == $wallet->id)>
                                                {{ $wallet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('array_wallet_id.'. $key)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('array_item_name.' . $key) is-invalid @enderror"
                                            name="array_item_name[{{ $key }}]" 
                                            id="array_item_name[{{ $key }}]" 
                                            value="{{ old('array_item_name.'. $key, $item->item_name) }}" autocomplete="off"
                                            form="expense" tabindex="{{ $index++ }}" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control harga @error('array_quantity.' . $key) is-invalid @enderror"
                                        name="array_quantity[{{ $key }}]" 
                                        id="array_quantity[{{ $key }}]" 
                                        value="{{ old('array_quantity.'. $key, $item->quantity) }}" autocomplete="off"
                                        form="expense" tabindex="{{ $index++ }}" required pattern="[0-9]+">
                                    </td>
                                    <td>
                                       <input type="text" class="form-control harga @error('array_price.' . $key) is-invalid @enderror"
                                            name="array_price[{{ $key }}]" 
                                            id="array_price[{{ $key }}]" 
                                            value="{{ old('array_price.'. $key, $item->price) }}" autocomplete="off"
                                            form="expense" tabindex="{{ $index++ }}" required>
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('expense-detail.destroy', [
                                                'expense' => $expense->getKey(),
                                                'expense_detail' => $item->getKey(),
                                            ]) }}"
                                            method="post">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="expense_detail_id"
                                                value="{{ $item->getKey() }}">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <input type="hidden" name="array_max" value="{{ $key ?? 0 }}" form="expense">
                        </tbody>
                    </table>
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

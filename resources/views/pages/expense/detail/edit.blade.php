@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table Expense --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense.show', $expenseDetail->expense_id) }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expense-detail.update', ['expense_detail' => $expenseDetail->id]) }}"
                        class="row" method="post">
                        @method('PUT')
                        @csrf
                        <div class="form-group col-6">
                            <label for="wallet-input">Nama Dompet<span class="text-small text-danger">*</span></label>
                            <select class="form-control select2 @error('wallet') is-invalid @enderror" name="wallet_id"
                                id="requested-by-select">
                                <option value="">-</option>
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}" @if ($expenseDetail->wallet_id === $wallet->id) selected @endif>
                                        {{ $wallet->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wallet')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="item-name-input">Nama Barang<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                name="item_name" id="item-name-input" placeholder=""
                                value="{{ $expenseDetail->item_name }}">
                            @error('item_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="quantity-input">Kuantitas Barang<span
                                    class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('quantity') is-invalid @enderror"
                                name="quantity" id="quantity-input" placeholder="" value="{{ $expenseDetail->quantity }}">
                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="price-input">Harga Barang<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                                id="price-input" placeholder="" value="{{ $expenseDetail->price }}">
                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table Expense --}}
    </div>
    {{-- END ROW --}}
@endsection

@push('js')
    <script>
        formatAngka('#price-input')
        formatAngka('#quantity-input')
    </script>
@endpush

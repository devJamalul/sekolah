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
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expense-detail.store') }}" class="row" method="post">
                        <input type="hidden" name="expense_id" id="expense_id" value="{{ $expense->id }}">
                        @csrf
                        <div class="form-group col-6">
                            <label for="wallet-input">Nama Dompet<span class="text-small text-danger">*</span></label>
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
                        </div>
                        <div class="form-group col-6">
                            <label for="item-name-input">Nama Barang<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('item_name') is-invalid @enderror" name="item_name"
                                id="item-name-input" value="{{ old('item_name') }}">
                            @error('item_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="quantity-input">Kuantitas Barang<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                id="quantity-input" value="{{ old('quantity') }}">
                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="price-input">Harga Barang<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                                id="price-input" value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-12">
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-primary text-light">
                    Detail Barang
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Dompet</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Harga Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $jumlahBarang = 0;
                                $totalHarga = 0;
                            @endphp
                            @foreach ($expenseDetails as $expenseDetail)
                                <tr>
                                    <td>{{ $expenseDetail->wallet->name }}</td>
                                    <td>{{ $expenseDetail->item_name }}</td>
                                    <td>{{ number_format($expenseDetail->quantity, 0, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($expenseDetail->price, 0, ',', '.') }}</td>
                                    <td>
                                        {{-- todo: logika edit expense  --}}
                                        {{-- <a class="btn btn-warning"
                                            href="{{ route('expense-detail.edit', $expenseDetail->id) }}">Ubah</a> --}}
                                        <button class="btn btn-danger btn-sm" onclick="softDelete(this)"
                                            value="{{ $expenseDetail->id }}"
                                            data-redirect="{{ route('expense.show', $expenseDetail->expense->id) }}"
                                            data-url="{{ route('expense-detail.destroy', $expenseDetail->id) }}">Hapus</button>
                                    </td>
                                </tr>
                                @php
                                    $jumlahBarang += $expenseDetail->quantity;
                                    $totalHarga += $expenseDetail->price * $expenseDetail->quantity;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right text-primary font-weight-bold">Total</td>
                                <td class="text-primary font-weight-bold">{{ number_format($jumlahBarang, 0, ',', '.') }}
                                </td>
                                <td class="text-primary font-weight-bold">Rp. {{ number_format($totalHarga, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- END table expense detail --}}
    </div>
    {{-- END ROW --}}
@endsection

@push('js')
    <script>
        function softDelete(e) {

            const url = $(e).data('url')
            const name = $(e).data('name') ?? ''
            const redirect = $(e).data('redirect')

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Untuk menghapus data ' + name,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    cancelButton: 'btn btn-light waves-effect',
                    confirmButton: 'btn btn-primary waves-effect waves-light'
                },
                preConfirm: (e) => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve();
                        }, 50);
                    });
                }
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            _method: "DELETE"
                        },
                        url: url,
                        success: function(response) {
                            toastMessage("success", response.msg)
                            setTimeout(function() {
                                window.location = redirect;
                            }, 1000)
                        },
                        error: function(xhr, status, error) {
                            toastMessage("error", err.msg)
                        }
                    })
                }
            })
        }



        function toastMessage(status, msg) {
            Swal.fire({
                "title": msg,
                "text": "",
                "timer": 5000,
                "width": "32rem",
                "padding": "1.25rem",
                "showConfirmButton": false,
                "showCloseButton": true,
                "timerProgressBar": false,
                "customClass": {
                    "container": null,
                    "popup": null,
                    "header": null,
                    "title": null,
                    "closeButton": null,
                    "icon": null,
                    "image": null,
                    "content": null,
                    "input": null,
                    "actions": null,
                    "confirmButton": null,
                    "cancelButton": null,
                    "footer": null
                },
                "toast": true,
                "icon": status,
                "position": "top-end"
            })

        }
    </script>
@endpush

@push('js')
    <script>
        formatAngka('#price-input')
        formatAngka('#quantity-input')
    </script>
@endpush

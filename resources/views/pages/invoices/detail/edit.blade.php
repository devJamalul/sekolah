@extends('layout.master-page')


@section('content')
  {{-- start ROW --}}

  <div class="row">

    {{-- start table academy years --}}
    <div class="col-lg-12">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('invoice-details.index', $invoice->getKey()) }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
          Kembali
        </a>
      </div>
      <div class="card">
        <div class="card-body">
          <form action="{{ route('invoice-details.update', [$invoice->getKey(), $detail->getKey()]) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="note">Deskripsi</label>
                  <input type="text" class="form-control" id="note" aria-describedby="note"
                    value="{{ old('note', $invoice->note) }}" readonly>
                </div>
                <div class="form-group">
                  <label for="item_name">Nama Barang<span class="text-small text-danger">*</span></label>
                  <input type="text" class="form-control @error('item_name') is-invalid @enderror" name="item_name"
                    id="item_name" aria-describedby="item_name" value="{{ old('item_name', $detail->item_name) }}" autocomplete="off"
                    tabindex="1" autofocus>
                  @error('item_name')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="invoice_date">Tanggal Invoice</label>
                  <input type="text" class="form-control" id="invoice_date" aria-describedby="invoice_date"
                    value="{{ old('invoice_date', Laraindo\TanggalFormat::DateIndo($invoice->invoice_date)) }}" readonly>
                </div>
                <div class="form-group">
                  <label for="price">Harga<span class="text-small text-danger">*</span></label>
                  <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                    id="price" aria-describedby="price" value="{{ old('price', $detail->price) }}" autocomplete="off" tabindex="2">
                  @error('price')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="due_date">Jatuh Tempo</label>
                  <input type="text" class="form-control" id="due_date" aria-describedby="due_date"
                    value="{{ old('due_date', Laraindo\TanggalFormat::DateIndo($invoice->due_date)) }}" readonly>
                </div>
                <div class="form-group mt-5">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Ubah</button>
                </div>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>

    {{-- END table academy years --}}
  </div>
  {{-- END ROW --}}
@endsection

@push('js')
  <script>
    formatAngka('#price')
  </script>
@endpush

@extends('layout.master-page')


@section('content')
  {{-- start ROW --}}

  <div class="row">

    {{-- start table academy years --}}
    <div class="col-lg-12">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
        <a href="{{ route('invoices.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
          Daftar Invoice
        </a>
      </div>
      <div class="card">
        <div class="card-body">
          @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
            <form action="{{ route('invoice-details.store', $invoice->getKey()) }}" method="post">
              @csrf
          @endif

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="note">Deskripsi</label>
                <input type="text" class="form-control" id="note" aria-describedby="note"
                  value="{{ old('note', $invoice->note) }}" readonly>
              </div>
              @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
                <div class="form-group">
                  <label for="item_name">Nama Barang<span class="text-small text-danger">*</span></label>
                  <input type="text" class="form-control @error('item_name') is-invalid @enderror" name="item_name"
                    id="item_name" aria-describedby="item_name" value="{{ old('item_name') }}" autocomplete="off"
                    tabindex="1" autofocus>
                  @error('item_name')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              @endif
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="invoice_date">Tanggal Invoice</label>
                <input type="text" class="form-control" id="invoice_date" aria-describedby="invoice_date"
                  value="{{ old('invoice_date', Laraindo\TanggalFormat::DateIndo($invoice->invoice_date)) }}" readonly>
              </div>
              @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
                <div class="form-group">
                  <label for="price">Harga<span class="text-small text-danger">*</span></label>
                  <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
                    id="price" aria-describedby="price" value="{{ old('price') }}" autocomplete="off" tabindex="2">
                  @error('price')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              @endif
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="due_date">Jatuh Tempo</label>
                <input type="text" class="form-control" id="due_date" aria-describedby="due_date"
                  value="{{ old('due_date', Laraindo\TanggalFormat::DateIndo($invoice->due_date)) }}" readonly>
              </div>
              @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
                <div class="form-group mt-5">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
              @endif
            </div>
          </div>
          @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
            </form>
          @endif
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
                <th style="width: 10%">No</th>
                <th>Nama Barang</th>
                <th style="width: 30%">Harga</th>
                @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
                  <th style="width: 20%">Aksi</th>
                @else
                  <th style="width: 20%">Metode Pembayaran</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @php
                $total = 0;
              @endphp
              @forelse ($details as $key => $detail)
                @php
                  $total += $detail->price;
                @endphp
                <tr>
                  <td scope="row">{{ $loop->iteration }}</td>
                  <td>{{ $detail->item_name }}</td>
                  <td>Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                  @if ($invoice->payment_status == App\Models\Invoice::STATUS_PENDING)
                    <td>
                      <div class="d-flex gap-2">
                        <a href="{{ route('invoice-details.edit', ['invoice' => $invoice->getKey(), 'invoice_detail' => $detail->getKey()]) }}"
                          name="ubah{{ $key }}" id="ubah{{ $key }}"
                          class="btn btn-primary btn-sm mr-1" role="button">Ubah</a>
                        <form action="{{ route('invoice-details.destroy', [$invoice->getKey(), $detail->getKey()]) }}"
                          method="post">
                          @csrf
                          @method('DELETE')
                          <button type="submit" name="hapus{{ $key }}" id="hapus{{ $key }}"
                            class="btn btn-danger btn-sm" href="#" role="button">Hapus</button>
                        </form>
                      </div>
                    </td>
                  @else
                    <td>
                      {{ $detail->wallet->name }}
                    </td>
                  @endif
                </tr>
              @empty
                <tr>
                  <td class="text-center font-weight-bold" colspan="4">Data masih kosong</td>
                </tr>
              @endforelse
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2" class="text-right font-weight-bold">Total Harga</td>
                <td class="font-weight-bolder text-primary">Rp. {{ number_format($total, 0, ',', '.') }}</td>
              </tr>
            </tfoot>
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
    formatAngka('#price')
  </script>
@endpush

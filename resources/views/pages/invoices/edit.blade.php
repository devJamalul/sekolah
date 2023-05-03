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
          <form action="{{ route('invoices.update', $invoice->getKey()) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="note">Deskripsi<span class="text-small text-danger">*</span></label>
                  <input type="text" class="form-control @error('note') is-invalid @enderror" name="note"
                    id="note" aria-describedby="note" value="{{ old('note', $invoice->note) }}" autocomplete="off" tabindex="1" autofocus>
                  @error('note')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="invoice_date">Tanggal Invoice<span class="text-small text-danger">*</span></label>
                  <input type="date" class="form-control @error('invoice_date') is-invalid @enderror"
                    name="invoice_date" id="invoice_date" aria-describedby="invoice_date"
                    value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" autocomplete="off" tabindex="3">
                  @error('invoice_date')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="invoice_number">No. Invoice<span class="text-small text-danger">*</span></label>
                  <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" name="invoice_number"
                    id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" autocomplete="off" tabindex="2">
                  @error('invoice_number')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="due_date">Jatuh Tempo<span class="text-small text-danger">*</span></label>
                  <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date"
                    id="due_date" aria-describedby="due_date" value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}" autocomplete="off" tabindex="4">
                  @error('due_date')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
            </div>


            <div class="btn-group float-right mt-2">
              <button type="submit" class="btn btn-primary ">Lanjutkan</button>
              <button type="reset" class="btn btn-secondary ">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- END table academy years --}}
  </div>
  {{-- END ROW --}}
@endsection

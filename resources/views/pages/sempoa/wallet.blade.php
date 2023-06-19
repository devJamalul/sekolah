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
                    <form action="{{ route('sempoa-wallet.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        @foreach ($wallets as $key => $wallet)
                            <div class="form-group row">
                                <label for="wallet" class="col-sm-4 col-form-label">{{ $wallet->name }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="wallet[]" name="wallet[]"
                                        value="{{ old('wallet') }}" autocomplete="off"
                                        placeholder="Kode Akun dompet {{ $wallet->name }}">
                                </div>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary offset-sm-4">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        {{-- END table invoices --}}
    </div>
    {{-- END ROW --}}
@endsection

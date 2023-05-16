@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table Wallet --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('wallet.topup.store', $wallet->getKey()) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="init-value-input">Nominal<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('nominal') is-invalid @enderror" name="nominal"
                                id="nominal-input" value="{{ old('nominal') }}" autocomplete="off" autofocus tabindex="1">
                            @error('nominal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn float-right btn-primary" tabindex="2">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table Wallet --}}
    </div>
    {{-- END ROW --}}
@endsection

@push('js')
    <script>
        formatAngka('#nominal-input')
    </script>
@endpush

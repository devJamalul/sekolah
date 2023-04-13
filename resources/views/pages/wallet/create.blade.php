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
                    <form action="{{ route('wallet.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name-input">Nama Dompet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                id="name-input" placeholder="">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="init-value-input">Pemasukan Saldo</label>
                            <input type="text" class="form-control @error('init_value') is-invalid @enderror" name="init_value"
                                id="init_value-input" placeholder="" pattern="[0-9]+">
                            @error('init_value')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">SIMPAN</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table Wallet --}}
    </div>
    {{-- END ROW --}}

@endsection

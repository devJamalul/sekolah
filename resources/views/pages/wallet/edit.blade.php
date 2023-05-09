@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('wallet.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('wallet.update', ['wallet' => $wallet->id]) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name-input">Nama Dompet<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control  @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $wallet->name) }}" id="name-input" placeholder="">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="init-value-input">Saldo Awal<span class="text-small text-danger">*</span></label>
                            <input type="text" class="form-control @error('init_value') is-invalid @enderror"
                                name="init_value" value="{{ old('init_value', $wallet->init_value) }}" id="init_value-input">
                            @error('init_value')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
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
        formatAngka('#init_value-input')
    </script>
@endpush

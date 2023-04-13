@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table tuitions type --}}
        <div class="col-lg-6">

            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('payment-type.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>


            @error('school_id')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('payment-type.update', ['payment_type' => $paymentType->id]) }}" method="post">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                        <div class="form-group">
                            <label for="year-academy-input">Tipe Pembayaran</label>
                            <input type="text" class="form-control  @error('name') is-invalid @enderror" name="name"
                                value="{{ $paymentType->name }}" id="year-academy-input">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="payment-type-input">Wallet</label>
                            <select name="wallet_id" id=""
                                class="form-control @error('wallet_id') is-invalid @enderror">
                                <option value="">-</option>
                                @foreach ($wallet as $opt)
                                    <option value="{{ $opt->id }}" @selected(old('wallet_id', $paymentType->wallet_id) == $opt->id)> {{ $opt->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('wallet_id')
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

        {{-- END table tuitions type --}}
    </div>
    {{-- END ROW --}}
@endsection

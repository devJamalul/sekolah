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
                    @if ($accounts)
                        <form action="{{ route('sempoa-wallet.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            @foreach ($wallets as $key => $wallet)
                                @php
                                    $kode = App\Models\SempoaWallet::firstWhere('wallet_id', $wallet->getKey());
                                @endphp
                                <input type="hidden" name="wallet_id[]" value="{{ $wallet->getKey() }}">
                                <div class="form-group row">
                                    <label for="wallet" class="col-sm-4 col-form-label">
                                        Akun {{ $wallet->name }}
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2" name="wallet[]" id="wallet[]">
                                            <option value="">Pilih...</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account['kode'] }}" @selected($account['kode'] == $kode?->account)>
                                                    {{ $account['kode'] . ' - ' . $account['akun'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('wallet.' . $key)
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary offset-sm-4">Simpan</button>
                        </form>
                    @else
                        <p>Belum terhubung dengan Sempoa.</p>
                    @endif
                </div>
            </div>
        </div>
        {{-- END table invoices --}}
    </div>
    {{-- END ROW --}}
@endsection

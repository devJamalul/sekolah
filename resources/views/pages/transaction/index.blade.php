@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table schools --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Siswa / NIS</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                id="name" aria-describedby="name" value="{{ old('name') }}" autocomplete="off" autofocus>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input name="cari" id="cari" class="btn btn-primary float-right" type="submit"
                            value="Cari">
                    </form>
                </div>
            </div>
        </div>
        {{-- END table schools --}}

    </div>
    {{-- END ROW --}}
@endsection

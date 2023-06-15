@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('reset-user-password.update', $user->getKey()) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name-input">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                id="name-input" autocomplete="off" value="{{ old('name', $user->name) }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="password-input">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                                id="password-input" autocomplete="off" value="{{ old('password') }}" autocomplete="off" autofocus>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation-input">Konfirmasi</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                id="password_confirmation-input" autocomplete="off" value="{{ old('password_confirmation') }}" autocomplete="off">
                        </div>

                        <button type="submit" class="btn float-right btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    {{-- END ROW --}}
@endsection

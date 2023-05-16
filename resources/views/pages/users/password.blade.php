@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('edit-password.update') }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="old_password">Password Lama</label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                name="old_password" id="old_password" autocomplete="off" value="{{ old('old_password') }}"
                                autofocus tabindex="1">
                            @error('old_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" autocomplete="off" tabindex="2">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" id="password_confirmation" autocomplete="off" tabindex="3">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary float-right" tabindex="4">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    {{-- END ROW --}}
@endsection

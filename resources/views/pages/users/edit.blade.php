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
          <form action="{{ route('users.update', $user->getKey()) }}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label for="name-input">Nama</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                id="name-input" autocomplete="off" value="{{ old('name', $user->name) }}">
              @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="email-input">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                id="email-input" autocomplete="off" value="{{ old('email', $user->email) }}">
              @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            @php
              $roles = [App\Models\User::ROLE_ADMIN_YAYASAN, App\Models\User::ROLE_ADMIN_SEKOLAH, App\Models\User::ROLE_KEPALA_SEKOLAH, App\Models\User::ROLE_TATA_USAHA, App\Models\User::ROLE_BENDAHARA];
            @endphp

            <div class="form-group">
              <label for="school-select">Jabatan</label>
              <select class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" id="school-select">
                <option value="">Pilih jabatan...</option>
                @foreach ($roles as $role)
                  <option value="{{ $role }}" @selected($role == old('jabatan', $user->jabatan))>
                    {{ str($role)->title() }}
                  </option>
                @endforeach
              </select>
              @error('jabatan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-default">Batal</button>
          </form>
        </div>
      </div>
    </div>

  </div>
  {{-- END ROW --}}
@endsection

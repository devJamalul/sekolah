@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('edit-profile.update') }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col">
                                {{-- Nama --}}
                                <div class="form-group">
                                    <label for="name-input">Nama<span class="text-small text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name-input" autocomplete="off"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="email-input">Email<span class="text-small text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" id="email-input" autocomplete="off"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Jabatan --}}
                                <div class="form-group">
                                    <label for="jabatan">Jabatan</label>
                                    <input type="email" class="form-control @error('jabatan') is-invalid @enderror"
                                        name="jabatan" id="jabatan" autocomplete="off"
                                        value="{{ old('jabatan', str($user->jabatan)->title) }}" readonly>
                                </div>

                                @unlessrole(['super admin', 'ops admin'])
                                    <input type="hidden" name="biodata">
                                    {{-- Jenis kelamin --}}
                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin<span class="text-small text-danger">*</span></label>
                                        <select class="form-control @error('gender') is-invalid @enderror" name="gender"
                                            id="gender">
                                            @foreach (\App\Models\Staff::GENDERS as $gender)
                                                <option value="{{ $gender }}" @selected($user->staff->gender == $gender)>
                                                    {{ $gender }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Agama --}}
                                    <div class="form-group">
                                        <label for="religion">Agama<span class="text-small text-danger">*</span></label>
                                        <select class="form-control @error('religion') is-invalid @enderror" name="religion"
                                            id="religion">
                                            @foreach (\App\Models\Staff::RELIGIONS as $religion)
                                                <option value="{{ $religion }}" @selected($user->staff->religion == $religion)>
                                                    {{ $religion }}</option>
                                            @endforeach
                                        </select>
                                        @error('religion')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- DoB --}}
                                    <div class="form-group">
                                        <label for="dob">Tanggal Lahir<span class="text-small text-danger">*</span></label>
                                        <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                            name="dob" id="dob" autocomplete="off"
                                            value="{{ old('dob', $user->staff->dob?->format('Y-m-d')) }}">
                                        @error('dob')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                @endunlessrole
                            </div>

                            @unlessrole(['super admin', 'ops admin'])
                                <div class="col">
                                    {{-- Phone --}}
                                    <div class="form-group">
                                        <label for="phone_number">Nomor Telepon<span
                                                class="text-small text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                            name="phone_number" id="phone_number" autocomplete="off"
                                            value="{{ old('phone_number', $user->staff->phone_number) }}">
                                        @error('phone_number')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- NIK --}}
                                    <div class="form-group">
                                        <label for="nik">Nomor Induk Kependudukan (KTP)<span
                                                class="text-small text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                            name="nik" id="nik" autocomplete="off"
                                            value="{{ old('nik', $user->staff->nik) }}">
                                        @error('nik')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- NIP --}}
                                    <div class="form-group">
                                        <label for="nip">Nomor Induk Pegawai (NIP)</label>
                                        <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                            name="nip" id="nip" autocomplete="off"
                                            value="{{ old('nip', $user->staff->nip) }}">
                                        @error('nip')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- NIDN --}}
                                    <div class="form-group">
                                        <label for="nidn">Nomor Induk Dosen Nasional (NIDN)</label>
                                        <input type="text" class="form-control @error('nidn') is-invalid @enderror"
                                            name="nidn" id="nidn" autocomplete="off"
                                            value="{{ old('nidn', $user->staff->nidn) }}">
                                        @error('nidn')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endunlessrole

                        </div>


                        <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    {{-- END ROW --}}
@endsection

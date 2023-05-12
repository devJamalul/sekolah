@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('schools.profile-update') }}" class="row" method="post">
                        @csrf
                        @method('put')
                        <div class="col-6">
                            <p class="font-weight-bold h5" id="title-school">Informasi Sekolah</p>
                            <hr style="border-top: 1px dashed #2e3a61">
                            <div class="form-group">
                                <label for="name-input">Nama Sekolah <small class="text-danger">*</small> </label>
                                <input type="text" class="form-control   @error('school_name') is-invalid @enderror"
                                    value="{{ old('school_name', $school->school_name) }}" name="school_name"
                                    id="name-input" autocomplete="off">
                                @error('school_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="form-group col-5">
                                    <label>Provinsi <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control @error('province') is-invalid @enderror"
                                        name="province" autocomplete="off" value="{{ old('province', $school->province) }}">
                                    @error('province')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label>Kota <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        name="city" value="{{ old('city', $school->city) }}" autocomplete="off">
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-3">
                                    <label>Kode Pos <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                        name="postal_code" value="{{ old('postal_code', $school->postal_code) }}"
                                        autocomplete="off">
                                    @error('postal_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-12">
                                    <label>Alamat <small class="text-danger">*</small></label>
                                    <textarea name="address" id="" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $school->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <label>Tingkatan <small class="text-danger">*</small></label>

                                    <select name="grade" class="form-control select2 @error('grade') is-invalid @enderror"
                                        id="">
                                        <option value=""></option>

                                        @foreach ($grade_school as $grade)
                                            <option value="{{ $grade }}" @selected(old('grade', $grade) == $school->grade)>
                                                {{ $grade }}</option>
                                        @endforeach
                                    </select>
                                    @error('grade')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <label>Email <small class="text-danger">*</small> </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" autocomplete="off" value="{{ old('email', $school->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-12">
                                    <label>Nomor Telepon <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone', $school->phone) }}" autocomplete="off">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <p class="font-weight-bold h5">Informasi Pimpinan Sekolah</p>
                            <hr style="border-top: 1px dashed #2e3a61">
                            <div class="form-group col-12">
                                <label>Nama <small class="text-danger">*</small> </label>
                                <input type="text"
                                    class="form-control @error('foundation_head_name') is-invalid @enderror"
                                    name="foundation_head_name"
                                    value="{{ old('foundation_head_name', $school->foundation_head_name) }}"
                                    autocomplete="off">
                                @error('foundation_head_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <label>Nomor Telepon <small class="text-danger">*</small> </label>
                                <input type="text"
                                    class="form-control @error('foundation_head_tlpn') is-invalid @enderror"
                                    name="foundation_head_tlpn"
                                    value="{{ old('foundation_head_tlpn', $school->foundation_head_tlpn) }}"
                                    autocomplete="off">
                                @error('foundation_head_tlpn')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <p class="font-weight-bold h5">Informasi Penanggung Jawab</p>
                            <hr style="border-top: 1px dashed #2e3a61">
                            <div class="form-group col-12">
                                <label>Nama <small class="text-danger">*</small> </label>
                                <input type="text" class="form-control @error('name_pic') is-invalid @enderror"
                                    name="name_pic" value="{{ $school->staf?->user?->name }}" readonly
                                    autocomplete="off">
                                @error('name_pic')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <label>Email <small class="text-danger">*</small> </label>
                                <input type="email" value="{{ $school->staf?->user?->email }}"
                                    class="form-control @error('email_pic') is-invalid @enderror" readonly
                                    name="email_pic" autocomplete="off">
                                @error('email_pic')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="btn-group float-right mr-3 mt-2">
                                <button type="submit" class="btn btn-primary ">Simpan</button>
                                <button type="reset" class="btn btn-secondary ">Batal</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table academy years --}}
    </div>
    {{-- END ROW --}}
@endsection

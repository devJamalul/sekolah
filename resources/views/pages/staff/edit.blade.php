@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table staff type --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('staff.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>

            @error('school_id')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror
            <div class="card accordion" id="accordionExample">
                <form action="{{ route('staff.update', $staff->getKey()) }}" method="POST" enctype="multipart/form-data"
                    class="p-3">
                    @csrf

                    @method('PUT')
                    {{-- Student Information Accordion --}}
                    <div class="card">

                        {{-- Accordion Button --}}
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#informationAccordion" aria-expanded="true"
                                    aria-controls="informationAccordion">
                                    <span class="text-lg text-dark">Informasi Staff/Guru</span>
                                </button>
                            </h2>
                        </div>
                        {{-- End Accordion Button --}}

                        {{-- Accordion Content --}}
                        <div id="informationAccordion" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="card-body">

                                    {{-- Nama & Date Of Birth --}}
                                    <div class="row">
                                        <input type="hidden" name="school_id" value="{{ session('school_id') }}">

                                        {{-- Name --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="name">Nama<span
                                                        class="text-small text-danger">*</span></label>
                                                <input type="text" name="name" id="name"
                                                    value="{{ old('name', $staff->name) }}"
                                                    class="form-control @error('name') is-invalid @enderror" required>
                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End Name --}}

                                        {{-- Date Of Birth --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="dob">Tanggal Lahir<span
                                                        class="text-small text-danger">*</span> </label>
                                                <input type="date" name="dob" id="dob"
                                                    value="{{ old('dob', $staff->dob?->format('Y-m-d')) }}"
                                                    class="form-control @error('dob') is-invalid @enderror" required>
                                                @error('dob')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End Date Of Birth --}}

                                    </div>
                                    {{-- End Nama & Date Of Birth --}}

                                    {{-- Gender & Religion --}}
                                    <div class="row">

                                        {{-- Gender --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="gender">Jenis Kelamin<span
                                                        class="text-small text-danger">*</span>


                                                </label>
                                                <select id="gender" name="gender"
                                                    class="form-control @error('gender') is-invalid @enderror" required>
                                                    <option value="">--- Pilih ---</option>
                                                    @foreach (\App\Models\Staff::GENDERS as $gender)
                                                        <option value="{{ $gender }}" @selected(old('gender', $staff->gender) == $gender)>
                                                            {{ $gender }}</option>
                                                    @endforeach
                                                </select>

                                                @error('gender')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End Gender --}}

                                        {{-- Religion --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="religion">Agama<span
                                                        class="text-small text-danger">*</span></label>
                                                <select id="religion" name="religion"
                                                    class="form-control @error('religion') is-invalid @enderror" required>
                                                    <option value="">--- Pilih ---</option>
                                                    @foreach (\App\Models\Staff::RELIGIONS as $religion)
                                                        <option value="{{ $religion }}" @selected(old('religion', $staff->religion) == $religion)>
                                                            {{ $religion }}</option>
                                                    @endforeach
                                                </select>
                                                @error('religion')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End Religion --}}

                                    </div>
                                    {{-- End Gender & Address --}}

                                    {{-- No Kartu Keluarga & Email --}}
                                    <div class="row">

                                        {{-- KK --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="family_card_number">Nomor Kartu Keluarga<span
                                                        class="text-small text-danger">*</span></label>
                                                <input type="number" name="family_card_number" id="family_card_number"
                                                    value="{{ old('family_card_number', $staff->family_card_number) }}"
                                                    class="form-control @error('family_card_number') is-invalid @enderror"
                                                    required>
                                                @error('family_card_number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End KK --}}



                                    </div>
                                    {{-- End No Kartu Keluarga & Email --}}

                                    {{-- NIK & Phone --}}
                                    <div class="row">

                                        {{-- NIK --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="nik">NIK<span
                                                        class="text-small text-danger">*</span></label>
                                                <input type="number" name="nik" id="nik"
                                                    value="{{ old('nik', $staff->nik) }}"
                                                    class="form-control @error('nik') is-invalid @enderror" required>
                                                @error('nik')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End NIK --}}

                                        {{-- Phone --}}
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="phone">No Telepon</label>
                                                <input type="text" name="phone_number"
                                                    value="{{ old('phone_number', $staff->phone_number) }}"
                                                    id="phone"
                                                    class="form-control @error('phone_number') is-invalid @enderror">
                                                @error('phone_number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- End Phone --}}

                                    </div>
                                    {{-- End NIK & Phone --}}

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="nip">NIP</label>
                                                <input type="number" name="nip"
                                                    value="{{ old('nip', $staff->nip) }}" id="nip"
                                                    class="form-control @error('nip') is-invalid @enderror">
                                                @error('nip')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="nidn">NIDN</label>
                                                <input type="number" name="nidn"
                                                    value="{{ old('nidn', $staff->nidn) }}" id="nidn"
                                                    class="form-control @error('nidn') is-invalid @enderror">
                                                @error('nidn')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Address --}}
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="address">Alamat<span
                                                        class="text-small text-danger">*</span></label>
                                                <textarea name="address" id="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                                                    required>{{ old('address', $staff->address) }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- End Address --}}

                            </div>
                        </div>
                    </div>
                    {{-- End Accordion Content --}}
                    {{-- Student Documents Accordion --}}
                    <div class="card">

                        {{-- Accordion Button --}}
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#documentsAccordion" aria-expanded="true"
                                    aria-controls="documentsAccordion">
                                    <span class="text-lg text-dark">Berkas Staff/Guru</span>
                                </button>
                            </h2>
                        </div>
                        {{-- End Accordion Button --}}

                        {{-- Accordion Content --}}
                        <div id="documentsAccordion" class="collapse show" aria-labelledby="headingThree"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="card-body">

                                    {{-- Student's Photo --}}
                                    <div class="col">
                                        <div class="form-group has-validation">

                                            <div class="">
                                                <label for="file_photo">Foto Staff/Guru</label>
                                            </div>

                                            <img src="{{ $staff->file_photo }}" id="file_photo_preview"
                                                class="img-thumbnail img-fluid col-md-2" alt="Student's Photo">

                                            <div class="custom-file">
                                                <input type="file" name="file_photo" accept="image/*"
                                                    class="custom-file-input form-control @error('file_photo') is-invalid @enderror"
                                                    id="file_photo">
                                                <label class="custom-file-label" for="file_photo"
                                                    data-browse="Pilih Berkas">Unggah Berkas...</label>
                                            </div>
                                            @error('file_photo')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Student's Photo --}}

                                    {{-- Student's Birth Certificate --}}
                                    <div class="col">
                                        <div class="form-group">

                                            <div>
                                                <label for="file_birth_certificate" class="font-weight-bold">Akta
                                                    Kelahiran</label>
                                            </div>

                                            <img src="{{ $staff->file_birth_certificate }}"
                                                id="file_birth_certificate_preview"
                                                class="img-thumbnail img-fluid col-md-3"
                                                alt="Student's Birth Certificate">

                                            <div class="custom-file">
                                                <input type="file" accept="image/*"
                                                    class="custom-file-input @error('file_birth_certificate') is-invalid @enderror"
                                                    name="file_birth_certificate" id="file_birth_certificate">
                                                <label class="custom-file-label" for="file_birth_certificate"
                                                    id="birth_certificate_label" data-browse="Pilih Berkas">Unggah
                                                    Berkas...</label>
                                            </div>
                                            @error('file_birth_certificate')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Student's Birth Certificate --}}

                                    {{-- Student's Family Card --}}
                                    <div class="col">
                                        <div class="form-group">

                                            <div>
                                                <label for="file_family_card" class="font-weight-bold">Kartu
                                                    Keluarga</label>
                                            </div>

                                            <img src="{{ $staff->file_family_card }}" id="file_family_card_preview"
                                                class="img-thumbnail img-fluid col-md-3" alt="Student's Family Card">


                                            <div class="custom-file">
                                                <input type="file" accept="image/*"
                                                    class="custom-file-input @error('file_family_card') is-invalid @enderror"
                                                    name="file_family_card" id="file_family_card">
                                                <label class="custom-file-label" for="file_family_card"
                                                    data-browse="Pilih Berkas">Unggah Berkas...</label>
                                            </div>
                                            @error('file_family_card')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Student's Family Card --}}

                                </div>
                            </div>
                        </div>
                        {{-- End Accordion Content --}}

                    </div>
                    {{-- End Student Documents Accordion --}}

                    <div class="p-4">
                        <button type="submit" class="btn btn-primary float-right">Ubah</button>
                    </div>
            </div>
            {{-- End Student Information Accordion --}}


            </form>
        </div>
    </div>

    {{-- END table tuition type --}}
    </div>
    {{-- END ROW --}}
@endsection


@push('js')
    <script>
        // Files Input
        document.querySelector('#file_birth_certificate').addEventListener('change', function(e) {
            var file = document.getElementById("file_birth_certificate").files[0];

            const preview = document.querySelector('#file_birth_certificate_preview')
            preview.classList = 'img-thumbnail img-fluid col-md-3'
            preview.src = URL.createObjectURL(file)

            e.target.nextElementSibling.innerText = file.name
        })

        document.querySelector('#file_photo').addEventListener('change', function(e) {
            var file = document.getElementById("file_photo").files[0];

            const preview = document.querySelector('#file_photo_preview')
            preview.classList = 'img-thumbnail img-fluid col-md-3'
            preview.src = URL.createObjectURL(file)

            e.target.nextElementSibling.innerText = file.name
        })

        document.querySelector('#file_family_card').addEventListener('change', function(e) {
            var file = document.getElementById("file_family_card").files[0];

            const preview = document.querySelector('#file_family_card_preview')
            preview.classList = 'img-thumbnail img-fluid col-md-3'
            preview.src = URL.createObjectURL(file)

            e.target.nextElementSibling.innerText = file.name
        })
        // End Files Input
    </script>
@endpush

@extends('layout.master-page')

@section('title', $title)

@section('content')

    <div class="col-lg-12">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-4 text-primary font-weight-bold">{{ $title }}</h1>
            <div>
                <a href="{{ route('students.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">Kembali</a>
            </div>
        </div>
        {{-- End Header --}}

        <div class="card accordion" id="accordionExample">
            <form action="{{ route('students.update', $student->getKey()) }}" method="POST" enctype="multipart/form-data"
                class="p-3">
                @method('PUT')
                @csrf

                {{ session('errors') }}

                {{-- Informasi Siswa Accordion --}}
                <div class="card">

                    {{-- Accordion Button --}}
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#informationAccordion" aria-expanded="true"
                                aria-controls="informationAccordion">
                                <span class="text-lg text-dark">Informasi Siswa</span>
                            </button>
                        </h2>
                    </div>
                    {{-- End Accordion Button --}}

                    {{-- Accordion Content --}}
                    <div id="informationAccordion" class="collapse show" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="card-body">

                                {{-- Name & Day Of Birth --}}
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Nama<span class="text-small text-danger">*</span></label>
                                            <input type="text" name="name" id="name"
                                                value="{{ old('name', $student->name) }}"
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
                                            <label for="dob">Tanggal Lahir<span class="text-small text-danger">*</span>
                                            </label>
                                            <input type="date" name="dob" id="dob"
                                                value="{{ old('dob', $student->dob->format('Y-m-d')) }}"
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
                                {{-- End Name & Date Of Birth --}}

                                {{-- Gender & Religion --}}
                                <div class="row">

                                    {{-- Gender --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="gender">Jenis Kelamin<span
                                                    class="text-small text-danger">*</span></label>
                                            <select id="gender" name="gender"
                                                class="form-control @error('gender') is-invalid @enderror" required>
                                                <option value="">--- Pilih ---</option>
                                                <option value="L" @selected(old('gender', $student->gender) == 'L')>Laki</option>
                                                <option value="P" @selected(old('gender', $student->gender) == 'P')>Perempuan</option>
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
                                            <label for="religion">Agama<span class="text-small text-danger">*</span></label>
                                            <select id="religion" name="religion"
                                                class="form-control @error('religion') is-invalid @enderror" required>
                                                <option value="">--- Pilih ---</option>
                                                <option value="islam" @selected(old('religion', $student->religion) == 'islam')>Islam</option>
                                                <option value="protestan" @selected(old('religion', $student->religion) == 'protestan')>Protestan</option>
                                                <option value="katolik" @selected(old('religion', $student->religion) == 'katolik')>Katolik</option>
                                                <option value="hindu" @selected(old('religion', $student->religion) == 'hindu')>Hindu</option>
                                                <option value="buddha" @selected(old('religion', $student->religion) == 'buddha')>Buddha</option>
                                                <option value="khonghucu @selected(old('religion', $student->religion) == 'khonghucu')">Khonghucu</option>
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
                                                value="{{ old('family_card_number', $student->family_card_number) }}"
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

                                    {{-- Email --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" name="email"
                                                value="{{ old('email', $student->email) }}" id="email"
                                                class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Email --}}

                                </div>
                                {{-- End No Kartu Keluarga & Email --}}

                                {{-- NIK & Phone --}}
                                <div class="row">

                                    {{-- NIK --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="nik">Nik<span class="text-small text-danger">*</span></label>
                                            <input type="number" name="nik" id="nik"
                                                value="{{ old('nik', $student->nik) }}"
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
                                                value="{{ old('phone_number', $student->phone_number) }}" id="phone"
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

                                {{-- NIS & NISN --}}
                                <div class="row">

                                    {{-- NIS --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="nis">NIS</label>
                                            <input type="number" name="nis" id="nis"
                                                value="{{ old('nis', $student->nis) }}"
                                                class="form-control @error('nis') is-invalid @enderror">
                                            @error('nis')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End NIS --}}

                                    {{-- NISN --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="nisn">NISN</label>
                                            <input type="number" name="nisn"
                                                value="{{ old('nisn', $student->nisn) }}" id="nisn"
                                                class="form-control @error('nisn') is-invalid @enderror">
                                            @error('nisn')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End NISN --}}

                                </div>
                                {{-- End NIS & NISN --}}

                                {{-- Address --}}
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="address">Alamat<span
                                                    class="text-small text-danger">*</span></label>
                                            <textarea name="address" id="address" rows="4" class="form-control @error('address') is-invalid @enderror"
                                                required>{{ old('address', $student->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- End Address --}}

                                <div class="dropdown-divider"></div>

                                {{-- Father Information Section --}}

                                {{-- Phone & Name --}}
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="father_name">Nama Ayah</label>
                                            <input type="text" name="father_name" id="father_name"
                                                value="{{ old('father_name', $student->father_name) }}"
                                                class="form-control @error('father_name') is-invalid @enderror">
                                            @error('father_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Name --}}

                                    {{-- Phone --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="father_phone_number">No telepon Ayah</label>
                                            <input type="text" name="father_phone_number"
                                                value="{{ old('father_phone_number', $student->father_phone_number) }}"
                                                id="father_phone_number"
                                                class="form-control @error('father_phone_number') is-invalid @enderror">
                                            @error('father_phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Phone --}}

                                </div>
                                {{-- End Phone & Name --}}

                                <div class="row">

                                    {{-- Email --}}
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="father_email">Email Ayah</label>
                                            <input type="text" name="father_email"
                                                value="{{ old('father_email', $student->father_email) }}"
                                                id="father_email"
                                                class="form-control @error('father_email') is-invalid @enderror">
                                            @error('father_email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Email --}}

                                </div>

                                {{-- Address --}}
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="father_address">Alamat Ayah</label>
                                            <textarea name="father_address" id="father_address" rows="4"
                                                class="form-control @error('father_address') is-invalid @enderror">{{ old('father_address', $student->father_address) }}</textarea>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="father_address_checkbox" id="father_address_checkbox">
                                                <label class="form-check-label" for="father_address_checkbox">Alamat Ayah
                                                    sama dengan Alamat Siswa </label>
                                            </div>
                                            @error('father_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- End Address --}}

                                {{-- Father Information Section --}}

                                <div class="dropdown-divider"></div>

                                {{-- Mother Information Section --}}

                                {{-- Name & Phone --}}
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="mother_name">Nama Ibu</label>
                                            <input type="text" name="mother_name" id="mother_name"
                                                value="{{ old('mother_name', $student->mother_name) }}"
                                                class="form-control @error('mother_name') is-invalid @enderror">
                                            @error('mother_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Name --}}

                                    {{-- Phone --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="mother_phone_number">No telepon Ibu</label>
                                            <input type="text" name="mother_phone_number"
                                                value="{{ old('mother_phone_number', $student->mother_phone_number) }}"
                                                id="mother_phone_number"
                                                class="form-control @error('mother_phone_number') is-invalid @enderror">
                                            @error('mother_phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Phone --}}

                                </div>
                                {{-- End Name & Phone --}}

                                <div class="row">

                                    {{-- Email --}}
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="mother_email">Email Ibu</label>
                                            <input type="text" name="mother_email"
                                                value="{{ old('mother_email', $student->mother_email) }}"
                                                id="mother_email"
                                                class="form-control @error('mother_email') is-invalid @enderror">
                                            @error('mother_email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Email --}}

                                </div>

                                {{-- Address --}}
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="mother_address">Alamat Ibu</label>
                                            <textarea name="mother_address" id="mother_address" rows="4"
                                                class="form-control @error('mother_address') is-invalid @enderror">{{ old('mother_address', $student->mother_address) }}</textarea>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="mother_address_checkbox" id="mother_address_checkbox">
                                                <label class="form-check-label" for="mother_address_checkbox">Alamat Ibu
                                                    sama dengan Alamat Siswa </label>
                                            </div>
                                            @error('mother_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- End Address --}}

                                {{-- Mother Information Section --}}

                                <div class="dropdown-divider"></div>

                                {{-- Guardian Information Section --}}

                                {{-- Name & Phone --}}
                                <div class="row">

                                    {{-- Name --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="guardian_name">Nama Wali</label>
                                            <input type="text" name="guardian_name" id="guardian_name"
                                                value="{{ old('guardian_name', $student->guardian_name) }}"
                                                class="form-control @error('guardian_name') is-invalid @enderror">
                                            @error('guardian_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Name --}}

                                    {{-- Phone --}}
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="guardian_phone_number">No telepon Wali</label>
                                            <input type="text" name="guardian_phone_number"
                                                value="{{ old('guardian_phone_number', $student->guardian_phone_number) }}"
                                                id="guardian_phone_number"
                                                class="form-control @error('guardian_phone_number') is-invalid @enderror">
                                            @error('guardian_phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Phone --}}

                                </div>
                                {{-- End Name & Phone --}}

                                <div class="row">

                                    {{-- Email --}}
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="guardian_email">Email Wali</label>
                                            <input type="text" name="guardian_email"
                                                value="{{ old('guardian_email', $student->guardian_email) }}"
                                                id="guardian_email"
                                                class="form-control @error('guardian_email') is-invalid @enderror">
                                            @error('guardian_email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- End Email --}}

                                </div>

                                {{-- Address --}}
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="guardian_address">Alamat Wali</label>
                                            <textarea name="guardian_address" id="guardian_address" rows="4"
                                                class="form-control @error('guardian_address') is-invalid @enderror">{{ old('guardian_address', $student->guardian_address) }}</textarea>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="guardian_address_checkbox" id="guardian_address_checkbox">
                                                <label class="form-check-label" for="guardian_address_checkbox">Alamat
                                                    Wali sama dengan Alamat Siswa </label>
                                            </div>
                                            @error('guardian_address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- End Address --}}

                                {{-- Guardian Information Section --}}

                            </div>
                        </div>
                    </div>
                    {{-- End Accordion Content --}}

                </div>
                {{-- End Informasi Siswa Accordion --}}

                {{-- Student Documents Accordion --}}
                <div class="card">

                    {{-- Accordion Button --}}
                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#documentsAccordion" aria-expanded="true"
                                aria-controls="documentsAccordion">
                                <span class="text-lg text-dark">Berkas Siswa</span>
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
                                            <label for="file_photo" class="font-weight-bold">Foto Siswa</label>
                                        </div>

                                        <div id="file_photo_container">
                                            @if ($student->file_photo)
                                            <img src="{{ $student->file_photo }}" id="file_photo_preview"
                                                class="img-thumbnail img-fluid col-md-2" alt="Student's Photo">
                                            @endif
                                        </div>

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

                                        <div id="file_birth_certificate_container">
                                            @if ($student->file_birth_certificate)
                                            <img src="{{ $student->file_birth_certificate }}"
                                                id="file_birth_certificate_preview"
                                                class="img-thumbnail img-fluid col-md-3"
                                                alt="Student's Birth Certificate">
                                            @endif
                                        </div>

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
                                            <label for="file_family_card" class="font-weight-bold">Kartu Keluarga</label>
                                        </div>

                                        <div id="file_family_card_container">
                                            @if ($student->file_family_card)
                                            <img src="{{ $student->file_family_card }}" id="file_family_card_preview"
                                                class="img-thumbnail img-fluid col-md-3" alt="Student's Family Card">
                                            @endif
                                        </div>

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

                {{-- Submit Button --}}
                <div class="p-4">
                    <button type="submit" class="btn float-right btn-primary">Ubah</button>
                </div>
                {{-- End Submit Button --}}

            </form>
        </div>
    </div>

    @push('js')
        <script>
            // Track Addresses
            var studentAddressValue = ""

            const studentAddressInput = document.querySelector('#address')
            const fatherAddressInput = document.querySelector('#father_address')
            const fatherAddressCheckbox = document.querySelector('#father_address_checkbox')
            const motherAddressInput = document.querySelector('#mother_address')
            const motherAddressCheckbox = document.querySelector('#mother_address_checkbox')
            const guardianAddressInput = document.querySelector('#guardian_address')
            const guardianAddressCheckbox = document.querySelector('#guardian_address_checkbox')

            studentAddressInput.addEventListener('change', (event) => {
                studentAddressValue = event.target.value

                if (fatherAddressCheckbox.checked) fatherAddressInput.value = studentAddressValue
                if (motherAddressCheckbox.checked) motherAddressInput.value = studentAddressValue
                if (guardianAddressCheckbox.checked) guardianAddressInput.value = studentAddressValue
            })

            fatherAddressCheckbox.addEventListener('change', (event) => {
                fatherAddressInput.value = studentAddressValue;

                if (event.target.checked) {
                    fatherAddressInput.disabled = true
                } else {
                    fatherAddressInput.disabled = false
                    fatherAddressInput.value = ""
                }
            })

            motherAddressCheckbox.addEventListener('change', (event) => {
                motherAddressInput.value = studentAddressValue;

                if (event.target.checked) {
                    motherAddressInput.disabled = true
                } else {
                    motherAddressInput.disabled = false
                    motherAddressInput.value = ""
                }
            })

            guardianAddressCheckbox.addEventListener('change', (event) => {
                guardianAddressInput.value = studentAddressValue;

                if (event.target.checked) {
                    guardianAddressInput.disabled = true
                } else {
                    guardianAddressInput.disabled = false
                    guardianAddressInput.value = ""
                }
            })
            // Track Addresses

            // Files Input
            document.querySelector('#file_birth_certificate').addEventListener('change', function(e) {
                var file = document.getElementById("file_birth_certificate").files[0];

                // Append element if there's no image
                if (!document.getElementById("file_birth_certificate_preview")) {
                    $('#file_birth_certificate_container')
                        .append('<img id="file_birth_certificate_preview" class="img-thumbnail img-fluid col-md-3" alt="Student Birth Certificate">')
                    file = document.getElementById("file_birth_certificate")?.files?.[0]
                }

                const preview = document.querySelector('#file_birth_certificate_preview')
                preview.src = URL.createObjectURL(file)

                e.target.nextElementSibling.innerText = file.name
            })

            document.querySelector('#file_photo').addEventListener('change', function(e) {
                var file = document.getElementById("file_photo").files[0];

                // Append element if there's no image
                if (!document.getElementById("file_photo_preview")) {
                    $('#file_photo_container')
                        .append('<img id="file_photo_preview" class="img-thumbnail img-fluid col-md-3" alt="Student Birth Certificate">')
                    file = document.getElementById("file_photo")?.files?.[0]
                }

                const preview = document.querySelector('#file_photo_preview')
                preview.src = URL.createObjectURL(file)

                e.target.nextElementSibling.innerText = file.name
            })

            document.querySelector('#file_family_card').addEventListener('change', function(e) {
                var file = document.getElementById("file_family_card").files[0];

                // Append element if there's no image
                if (!document.getElementById("file_family_card_preview")) {
                    $('#file_family_card_container')
                        .append('<img id="file_family_card_preview" class="img-thumbnail img-fluid col-md-3" alt="Student Birth Certificate">')
                    file = document.getElementById("file_family_card")?.files?.[0]
                }

                const preview = document.querySelector('#file_family_card_preview')
                preview.src = URL.createObjectURL(file)

                e.target.nextElementSibling.innerText = file.name
            })
            // End Files Input

            function init(params) {
                studentAddressValue = studentAddressInput.value
                if (fatherAddressInput.value == studentAddressValue) {
                    fatherAddressInput.value = studentAddressValue
                    fatherAddressCheckbox.checked = true
                    fatherAddressInput.disabled = true
                }
                if (motherAddressInput.value == studentAddressValue) {
                    motherAddressInput.value = studentAddressValue
                    motherAddressCheckbox.checked = true
                    motherAddressInput.disabled = true
                }
                if (guardianAddressInput.value == studentAddressValue) {
                    guardianAddressInput.value = studentAddressValue
                    guardianAddressCheckbox.checked = true
                    guardianAddressInput.disabled = true
                }
            }
            init()
        </script>
    @endpush

@endsection

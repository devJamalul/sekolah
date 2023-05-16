@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table schools --}}
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('transactions.index') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">Kembali</a>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white font-weight-bold">
                            Biodata Siswa
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td scope="row">Nama Siswa</td>
                                        <td class="text-primary font-weight-bold">{{ $student->name }}
                                            ({{ $student->gender }})</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">NIS</td>
                                        <td class="text-primary font-weight-bold">{{ $student->nis }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Kelas</td>
                                        <td class="text-primary font-weight-bold">
                                            {{ $student?->classrooms()->latest()->first()?->grade->grade_name .' ' .$student?->classrooms()->latest()->first()?->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Orang Tua</td>
                                        <td class="text-primary font-weight-bold">
                                            {{ $student->father_name }} <br />
                                            {{ $student->mother_name }} <br />
                                            {{ $student->guardian_name }} <br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Alamat</td>
                                        <td class="text-primary font-weight-bold">{{ $student->address }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Tanggal Lahir</td>
                                        <td class="text-primary font-weight-bold">{{ $student->dob->format('d F Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white font-weight-bold">
                            Transaksi
                        </div>
                        <div class="card-body">
                            <form action="{{ route('transactions.update', $student->getKey()) }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="student_tuition_id">Tagihan Biaya</label>
                                    <select class="form-control select2" name="student_tuition_id" id="student_tuition_id">
                                        @foreach ($student_tuitions as $student_tuition)
                                            <option value="{{ $student_tuition->getKey() }}" @selected(old('student_tuition_id') == $student_tuition->getKey())>
                                                {{ $student_tuition->note }} {{ $student_tuition->period->format('F Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_tuition_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="payment_type_id">Metode Pembayaran</label>
                                    <select class="form-control select2 @error('payment_type_id') is-invalid @enderror"
                                        name="payment_type_id" id="payment_type_id">
                                        <option value="">Pilih metode...</option>
                                        @foreach ($payment_types as $payment_type)
                                            <option value="{{ $payment_type->getKey() }}" @selected(old('payment_type_id') == $payment_type->getKey())>
                                                {{ $payment_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_type_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nominal">Nominal</label>
                                    <input type="text" class="form-control @error('nominal') is-invalid @enderror"
                                        name="nominal" id="nominal" aria-describedby="nominal"
                                        value="{{ old('nominal') }}" autocomplete="off">
                                    @error('nominal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary float-right">Simpan</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END table schools --}}

    </div>
    {{-- END ROW --}}
@endsection

@push('js')
    <script>
        formatAngka('#nominal')
    </script>
@endpush

@extends('layout.master-page')

@section('title', $title)

@section('content')

    <div class="col-lg-12">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            <a href="{{ route('staff.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">Kembali</a>
        </div>
        {{-- End Header --}}

        {{-- Student Data --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        Biodata Staff/Guru
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table col-6">
                                <tbody>
                                    <tr>
                                        <td scope="row">Nomor Induk Pegawai (NIP)</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->nip }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Nomor Induk Kependudukan (KTP)</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Nomor Induk Dosen Nasional (NIDN) </td>
                                        <td class="text-primary font-weight-bold">{{ $staff->nidn }}</td>
                                    </tr>

                                    <tr>
                                        <td scope="row">Nama </td>
                                        <td class="text-primary font-weight-bold">{{ $staff->name }}
                                            ({{ $staff->gender }})</td>
                                    </tr>


                                    <tr>
                                        <td scope="row">Tanggal Lahir</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->dob->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Alamat</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->address }}</td>
                                    </tr>

                                    <tr>
                                        <td scope="row">Agama</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->religion }}</td>
                                    </tr>


                                    <tr>
                                        <td scope="row">No Telephone</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->phone_number }}</td>
                                    </tr>

                                    <tr>
                                        <td scope="row">No Kartu Keluarga</td>
                                        <td class="text-primary font-weight-bold">{{ $staff->family_card_number }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Student Data --}}

    </div>
    {{-- END table schools --}}

@endsection

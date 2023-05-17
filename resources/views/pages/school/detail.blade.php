@extends('layout.master-page')

@section('title', $title)

@section('content')

    <div class="col-lg-12">

        {{-- Header --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            <a href="{{ route('schools.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">Kembali</a>
        </div>
        {{-- End Header --}}

        {{-- Student Data --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        Data Sekolah
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table col-12">
                                <tbody>
                                    <tr>
                                        <td scope="row">Nama Sekolah</td>
                                        <td class="text-primary font-weight-bold">{{ $school->school_name }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Provinsi</td>
                                        <td class="text-primary font-weight-bold">{{ $school->province }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Kota </td>
                                        <td class="text-primary font-weight-bold">{{ $school->city }}</td>
                                    </tr>

                                    <tr>
                                        <td scope="row">Kode Pos </td>
                                        <td class="text-primary font-weight-bold">{{ $school->postal_code }}</td>
                                    </tr>

                                    <tr>
                                        <td scope="row">Alamat</td>
                                        <td class="text-primary font-weight-bold">{{ $school->address }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Tingkatan</td>
                                        <td class="text-primary font-weight-bold">{{ $school->grade }}</td>
                                    </tr>

                                    <tr>
                                        <td scope="row">Email</td>
                                        <td class="text-primary font-weight-bold">{{ $school->email }}</td>
                                    </tr>


                                    <tr>
                                        <td scope="row">No Telephone</td>
                                        <td class="text-primary font-weight-bold">{{ $school->phone }}</td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        Data Informasi Pimpinan Sekolah
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table col-12">
                                <tbody>
                                    <tr>
                                        <td scope="row">Nama </td>
                                        <td class="text-primary font-weight-bold">{{ $school->foundation_head_name }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">No Telephone</td>
                                        <td class="text-primary font-weight-bold">{{ $school->foundation_head_tlpn }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        Data Informasi Penanggung Jawab
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table col-12">
                                <tbody>
                                    <tr>
                                        <td scope="row">Nama </td>
                                        <td class="text-primary font-weight-bold">{{ $school->staf?->user?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Email</td>
                                        <td class="text-primary font-weight-bold">{{ $school->staf?->user?->email }}</td>
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

@extends('layout.master-page')

@section('title', $title)

@section('content')

    {{-- Tuition Data --}}
    <div class="row">
        <div class="col-md-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('expense-outgoing.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Kembali
                </a>
            </div>
            <div class="card card-body">
                <div class="row">
                    <table class="table col-12">
                        <tbody>
                            <tr>
                                <td scope="row">No Pengeluaran Biaya</td>
                                <td class="text-primary font-weight-bold">{{ $expense->expense_number }}</td>
                            </tr>
                            <tr>
                                <td scope="row">Tanggal Pengeluaran Biaya</td>
                                <td class="text-primary font-weight-bold">{{ $expense->expense_date->format('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td scope="row">Nominal</td>
                                <td class="text-primary font-weight-bold">IDR
                                    {{ number_format($expense->price, 0, ', ', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Diajukan oleh</td>
                                <td class="text-primary font-weight-bold">{{ $expense->requested_by?->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Disetujui oleh</td>
                                <td class="text-primary font-weight-bold">{{ $expense->approved_by?->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td scope="row">Status</td>
                                <td class="text-primary font-weight-bold">
                                    @if ($expense->status == \App\Models\Expense::STATUS_REJECTED)
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif ($expense->status == \App\Models\Expense::STATUS_APPROVED)
                                        <span class="badge badge-success">Disetujui</span>
                                    @else
                                        <span class="badge badge-primary">Menunggu Persetujuan</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @can(['expense-outgoing.update'])
                        @if ($expense->status == \App\Models\Expense::STATUS_APPROVED)
                            <div class="col-12" style="width: 100%; display: flex; justify-content: end; ">
                                <form
                                    action="{{ route('expense-outgoing.update', ['expense_outgoing' => $expense->getKey()]) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')


                                    <div class="form-group">
                                        <input type="file" name="file_photo" accept=".doc, .docx, .pdf, image/*"
                                        class="custom-file-input form-control @error('file_photo') is-invalid @enderror"
                                        id="file_photo" required>
                                        <label class="custom-file-label" for="file_photo" id="file_photo"
                                        data-browse="Pilih Berkas">Unggah Berkas...</label>

                                        @error('file_photo')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <img id="file_photo_preview" class="col-12">

                                    <div style="margin-left: 65%">
                                        {{-- <button type="submit" name="action" value="reject"
                                        class="btn btn-danger ml-5">Tolak</button> --}}
                                        <button type="submit" name="action" value="approve"
                                        class="btn btn-primary ml-2">Realisasikan</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>
            {{-- End Tuition Data --}}
        </div>
    </div>
@endsection

@push('js')
    <script>

            // Files Input
            document.querySelector('#file_photo').addEventListener('change', function(e) {
                var file = document.getElementById("file_photo").files[0];

                // const preview = document.querySelector('#file_photo_preview')
                // preview.classList = 'img-thumbnail img-fluid col-md-3'
                // preview.src = URL.createObjectURL(file)

                e.target.nextElementSibling.innerText = file.name
            })

    </script>
@endpush

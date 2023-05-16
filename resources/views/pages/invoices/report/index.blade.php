@extends('layout.master-page')


@section('content')
    {{-- start ROW --}}

    <div class="row">

        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                <a href="{{ route('invoices.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-default shadow-sm">
                    Data Invoice
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.report-result') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="payment_status">Status Pembayaran</label>
                            <select class="form-control select2" name="payment_status" id="payment_status">
                                <option value="*" @selected(old('payment_status') == '*')>Semua</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(old('payment_status') == $status)>
                                        {{ str($status)->title }}</option>
                                @endforeach
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="range">Pilih Periode</label>
                            <input type="text" class="form-control @error('range') is-invalid @enderror" name="range"
                                id="range" aria-describedby="range" value="{{ old('range') }}" autocomplete="off">
                            @error('range')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="float-right">
                            <button type="submit" name="action" value="excel" class="btn btn-success ">Ekspor
                                Excel</button>
                            <button type="submit" name="action" value="pdf" class="btn btn-danger">Ekspor PDF</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    {{-- END ROW --}}
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#range span').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
            }

            $('#range').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf(
                        'month')]
                }
            }, cb);
            cb(start, end);
        });
    </script>
@endpush

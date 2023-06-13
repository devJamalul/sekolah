@extends('layout.master-page')


@push('css')
    <style>
        .no-border {
            border: 1;
            border-radius: 0;
            /* You may want to include this as bootstrap applies these styles too */
        }

        .left-radius {
            -webkit-border-top-left-radius: 10px;
            -webkit-border-bottom-left-radius: 10px;
            -moz-border-radius-topleft: 10px;
            -moz-border-radius-bottomleft: 10px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .right-radius {
            -webkit-border-top-right-radius: 10px;
            -webkit-border-bottom-right-radius: 10px;
            -moz-border-radius-topright: 10px;
            -moz-border-radius-bottomright: 10px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
    </style>
@endpush

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table school finaces type --}}
        <div class="col-lg-4">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
            </div>
            <div class="card ">
                <div class="card-body ">
                    <form action="{{ route('report-school-finances.show') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="wallet_id">Dana Dari</label>
                            <div class="input-group " style="borr-ra">
                                <select name="wallet_id" id="" class="form-control no-border "
                                    id="validationServerUsername" required aria-describedby="inputGroupPrepend3">
                                    @foreach ($wallet as $key => $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reportrange">Tanggal Dari</label>
                            <input type="text" class="form-control no-border" name="reportrange" id="reportrange"
                                id="validationServerUsername" aria-describedby="inputGroupPrepend3">
                        </div>
                        <div class="form-group">
                            <label for="reportrange">Tipe Uang</label>
                            <div class="input-group">
                                <select name="cashflow_type" id="" class="form-control no-border">
                                    <option value="all">Semua</option>
                                    <option value="init">Saldo Awal</option>
                                    <option value="in">Masuk</option>
                                    <option value="out">Keluar</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn float-right btn-primary ">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END table staff type --}}
    </div>
    {{-- END ROW --}}
@endsection
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush


@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange').val(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
        }
        $('input[name="reportrange"]').val('');
        $('input[name="reportrange"]').attr("placeholder", "-");
        $('input[name="reportrange"]').on("focus", function() {
            $("#reportrange").daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1,
                            'month').endOf(
                            'month')
                    ]
                }
            }, cb);
        });
    </script>
@endpush

@extends('layout.master-page')



@section('content')
    {{-- start ROW --}}
    <form action="{{ route('export-student-tuition') }}" target="_blank" method="POST" id="form-data">
        @csrf
        <div class="row">

            {{-- start table tuituion type --}}
            <div class="col-lg-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
                    <div class="btn-group">
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                Export
                            </button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" type="submit" name="export" value="excel">Excel</button>
                                <button class="dropdown-item" type="submit" name="export" value="pdf">PDF</button>
                            </div>
                        </div>

                        <a href="#" data-toggle="modal"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm p-2 filter">
                            Filter
                            <i class="fas fa-filter"></i> </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">

                        <table class="table table-bordered" id="student-tuitions" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Siswa</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Tingkatan</th>
                                    <th>Kelas</th>
                                    <th>Tipe Biaya</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Sisa Bayar</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7">Total Pembayaran</th>
                                    <th><span id="total_remaining_debt">0</span></th>
                                    <th colspan="3"><span id="total_payment">0</span></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            {{-- END table tuituion type --}}

            <div class="filter-warp">
                <div class="filter-content">
                    <h5 class="text-primary my-3">Filter</h5>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm " name="bill_num" id="bill_num"
                            placeholder="By Invoice">
                    </div>
                    <div class="form-group">
                        <select name="student" id="student" class="form-control form-control-sm select2"
                            data-placeholder="By Siswa">
                            <option value="" selected>-</option>
                            @foreach ($student as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->nis }}| {{ $row->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="status" id="status" class="form-control form-control-sm select2"
                            data-placeholder="By Status">
                            <option value="">-</option>
                            @foreach ($statusPayment as $key => $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="academy-year" id="academy-year" class="form-control form-control-sm select2"
                            data-placeholder="By Akademik">
                            <option value=""></option>

                            @foreach ($academicYear as $key => $row)
                                <option value="{{ $row->id }}">{{ $row->academic_year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="reportrange" id="reportrange"
                            aria-describedby="reportrange" value="{{ old('reportrange') }}" autocomplete="off">
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger filter">Tutup</button>
                        <button type="reset" class="btn btn-secondary filter" id="filter-reset">Reset</button>
                        <button type="button" class="btn btn-primary filter" id="filter-data">Filter</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- END ROW --}}
    </form>
@endsection


@push('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
    <link rel="stylesheet"
        href="https://datatables.net/release-datatables/extensions/FixedColumns/css/fixedColumns.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        table td {
            word-break: break-word;
            vertical-align: top;
            white-space: normal !important;
        }

        .filter-warp {
            width: 20%;
            height: 100vh;
            background: white;
            position: absolute;
            top: 0;
            display: none;
            padding: 20px 10px;
            right: 0;
            z-index: 99;
            box-shadow: -6px -1px 8px -1px rgba(92, 92, 100, 0.46);
            -webkit-box-shadow: -6px -1px 8px -1px rgba(92, 92, 100, 0.46);
            -moz-box-shadow: -6px -1px 8px -1px rgba(92, 92, 100, 0.46);
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://datatables.net/release-datatables/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var setFilter = {}
        var setQueryUrlData = {}
        $(".filter").click(function() {
            $(".filter-warp").toggle('slow');

        })
        $('[data-toggle="popover"]').popover();
        $(".select2-multi").select2({
            multiple: true,
            theme: "bootstrap",
            placeholder: function() {
                $(this).data('placeholder');
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function setQueryUrl(data, e) {
            setQueryUrlData[data] = $(e).val()
        }

        $(function() {
            var footerData = function(data) {
                let total_payment = data.json.total_payment
                let total_remaining_debt = data.json.total_remaining_debt
                console.log({
                    total_payment,
                    total_remaining_debt
                })
                var api = this.api();
                $(api.table().footer()).html(`
                    <tr>
                                <th colspan="7">Total Pembayaran</th>
                                <th><span id="total_remaining_debt">${total_remaining_debt}</span></th>
                                <th colspan="3"><span id="total_payment">${total_payment}</span></th>
                            </tr>
                    `)
            };
            const table = $("#student-tuitions")
            const url = route('datatable.report-student-tuitions');
            const columns = [{
                    data: 'bill_num'
                },
                {
                    data: 'name_student'
                },
                {
                    data: 'academy_year'
                },
                {
                    data: 'grade'
                },
                {
                    data: 'classrooms'
                },
                {
                    data: 'tuition_type'
                },
                {
                    data: 'payment_type'
                },
                {
                    data: 'remaining_debt'
                },
                {
                    data: 'grand_total'
                },
                {
                    data: 'status_payment'
                },
                {
                    data: 'date_invoice'
                }

            ];

            var reportTable = table.DataTable({
                scrollY: false,
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                info: false,
                searching: false,
                processing: true,
                serverSide: true,
                ajax: {
                    data: function(d) {
                        return $.extend(d, setFilter)
                    },
                    url: url,

                },
                initComplete: footerData,
                columns: columns,
                fixedColumns: true,
                columnDefs: [{
                        width: 200,
                        targets: 0
                    },
                    {
                        width: 200,
                        targets: 1
                    },
                    {
                        width: 100,
                        targets: 2
                    },
                    {
                        width: 50,
                        targets: 3
                    },
                    {
                        width: 50,
                        targets: 4
                    },
                    {
                        width: 50,
                        targets: 5
                    }, {
                        width: 50,
                        targets: 6
                    }, {
                        width: 200,
                        targets: 7
                    },
                    {
                        width: 100,
                        targets: 8
                    },
                    {
                        width: 150,
                        targets: 10
                    },
                ],
            });



            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
            }
            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder", "By Range Date Invoice");
            $('input[name="reportrange"]').on("focus", function() {
                $(this).daterangepicker({
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


            // cb(start, end);

            function setData() {
                let bill = $("#bill_num").val();
                let reportrange = $("#reportrange").val();
                let status = $("#status").val();
                let academyYear = $("#academy-year").val();
                let student = $("#student").val();

                if (reportrange != '') {
                    setFilter.reportrange = reportrange;
                }

                if (status !== '') {
                    setFilter.status = status;
                }

                if (academyYear !== '') {
                    setFilter.academyYear = academyYear;
                }

                if (student !== '') {
                    setFilter.student = student;
                }

                if (bill !== '') {
                    setFilter.bill = bill;
                }
            }


            $("#filter-data").click(function() {
                setData()

                reportTable.ajax.reload(function(data) {
                    let total_payment = data.total_payment
                    let total_remaining_debt = data.total_remaining_debt
                    $(reportTable.table().footer()).find("#total_remaining_debt").html(
                        total_remaining_debt)
                    $(reportTable.table().footer()).find("#total_payment").html(
                        total_payment)
                    alertInfo("Fillter Laporan Pembayaran Sekolah")
                })
            })


            $("#filter-reset").click(function() {

                setFilter = {}
                $('#student').val('').trigger('change')
                $('#status').val('').trigger('change')
                $('#academy-year').val('').trigger('change')
                $('input[name="reportrange"]').val('');
                $('input[name="reportrange"]').attr("placeholder", "By Range Date Invoice");
                reportTable.ajax.reload(function(data) {
                    let total_payment = data.total_payment
                    let total_remaining_debt = data.total_remaining_debt
                    console.log({
                        total_payment,
                        total_remaining_debt
                    })
                    $(reportTable.table().footer()).find("#total_remaining_debt").html(
                        total_remaining_debt)
                    $(reportTable.table().footer()).find("#total_payment").html(
                        total_payment)
                })
                alertInfo("Reset Fillter Laporan Pembayaran Sekolah")
            })

            function alertInfo(message) {
                Swal.fire({
                    "title": message,
                    "text": "",
                    "showConfirmButton": false,
                    "timerProgressBar": false,
                    "customClass": {
                        "container": null,
                        "popup": null,
                        "header": null,
                        "title": null,
                        "closeButton": null,
                        "icon": null,
                        "image": null,
                        "content": null,
                        "input": null,
                        "actions": null,
                        "confirmButton": null,
                        "cancelButton": null,
                        "footer": null
                    },
                    "icon": "info",
                    "toast": true,
                    "timer": 2000,
                    "position": "top-end",
                    "showCloseButton": true
                });
            }

        });
    </script>
@endpush

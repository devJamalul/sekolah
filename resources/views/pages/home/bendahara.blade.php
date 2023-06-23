@extends('layout.master-page')

@section('title', 'Dashboard')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ str(auth()->user()->getRoleNames()[0])->title }}</h1>
        <a href="{{ route('report-student-tuition') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $monthly_earnings_class }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $monthly_earnings_class }} text-uppercase mb-1">
                                {{ $monthly_earnings_label }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($monthly_earnings, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="{{ $monthly_earnings_icon }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $unpaid_earnings_class }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $unpaid_earnings_class }} text-uppercase mb-1">
                                {{ $unpaid_earnings_label }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($unpaid_earnings, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="{{ $unpaid_earnings_icon }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $partial_earnings_class }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $partial_earnings_class }} text-uppercase mb-1">
                                {{ $partial_earnings_label }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($partial_earnings, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="{{ $partial_earnings_icon }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $unpaid_tuitions_class }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $unpaid_tuitions_class }} text-uppercase mb-1">
                                {{ $unpaid_tuitions_label }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($unpaid_tuitions, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="{{ $unpaid_tuitions_icon }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
@endsection

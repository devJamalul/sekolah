@extends('layout.master-page')

@section('content')
  {{-- start ROW --}}

  <div class="row">

    {{-- start table schools --}}
    <div class="col-lg-4">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary font-weight-bold">{{ $title }}</h1>
      </div>
      <div class="card">
        <div class="card-body">
          <form action="{{ route('transaction-report.store') }}" method="post">
            @csrf
            <div class="form-group">
              <label for="reportrange">Pilih Periode</label>
              <input type="text" class="form-control @error('reportrange') is-invalid @enderror" name="reportrange"
                id="reportrange" aria-describedby="reportrange" value="{{ old('reportrange') }}" autocomplete="off">
              @error('reportrange')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <input name="cari" id="cari" class="btn btn-primary" type="submit" value="Cari">
            <button type="reset" class="btn btn-secondary">Batal</button>
          </form>
        </div>
      </div>
    </div>
    {{-- END table schools --}}

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
        $('#reportrange span').html(start.format('MMMM D, YYYY') + '-' + end.format('MMMM D, YYYY'));
      }

      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
            'month')]
        }
      }, cb);

      cb(start, end);

    });
  </script>
@endpush

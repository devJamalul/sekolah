<table width="100%" border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr>
      <th colspan="6">{{ str($filename)->title }}</th>
    </tr>
    <tr align="center">
      <th width="10%"><strong>No</strong></th>
      <th width="40%"><strong>Nomor Invoice</strong></th>
      <th width="20%"><strong>Tanggal Invoice</strong></th>
      <th width="20%"><strong>Jatuh Tempo</strong></th>
      <th width="20%"><strong>Status Pembayaran</strong></th>
      <th width="30%"><strong>Nilai Invoice</strong></th>
    </tr>
  </thead>
  <tbody>
    @forelse ($invoices as $row)
      <tr align="center">
        <th>{{ $loop->iteration }}</th>
        <td>{{ $row['invoice_number'] }}</td>
        <td>{{ date('d F Y', strtotime($row['invoice_date'])) }}</td>
        <td>{{ date('d F Y', strtotime($row['due_date'])) }}</td>
        <td>{{ str($row['payment_status'])->title }}</td>
        @php
          $total = 0;
        @endphp
        @foreach ($row['invoice_details'] as $detail)
          @php
            $total += $detail['price'];
          @endphp
        @endforeach
        <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
      </tr>
    @empty
      <tr>
        <th></th>
      </tr>
    @endforelse
  </tbody>
</table>

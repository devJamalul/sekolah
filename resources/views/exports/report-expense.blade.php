<table width="100%" border="1">
    <thead>
        <tr align="center">
            <th width="10%"><strong>No</strong></th>
            <th width="10%"><strong>No Pengeluaran Biaya</strong></th>
            <th width="30%"><strong>Tanggal Pengeluaran Biaya</strong></th>
            <th width="30%"><strong>Total Biaya</strong></th>
            <th width="30%"><strong>Tanggal Realisasi</strong></th>
            {{-- @dd($expense)
            @if ($expense->file_photo) --}}
                <th width="30%"><strong>Bukti</strong></th>
            {{-- @endif --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($expense as $index => $row)
            <tr align="center">
                <th>{{ $index+1 }}</th>
                <td>{{ $row['expense_number'] }}</td>
                <td>{{ $row['expense_date'] }}</td>
                <td>Rp. {{ number_format($row->expense_details()->sum(DB::raw('price * quantity')), 0, ', ', '.') }}</td>
                @if ($row['status'] == 'rejected')
                <td>-</td> 
                <td>-</td>
                @else
                <td>{{ $row['expense_outgoing_date'] }}</td>
                <td><a href="{{ $row['file_photo'] }}" target="_blank" download>Download</a></td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>


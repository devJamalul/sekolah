<table width="100%" border="1">
    <thead>
        <tr align="center">
            <th width="10%"><strong>No</strong></th>
            <th width="10%"><strong>No Pengeluaran Biaya</strong></th>
            <th width="30%"><strong>Tanggal</strong></th>
            <th width="30%"><strong>Total Biaya</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($expense as $index => $row)
            <tr align="center">
                <th>{{ $index+1 }}</th>
                <td>{{ $row['expense_number'] }}</td>
                <td>{{ $row['expense_date'] }}</td>
                <td>Rp. {{ number_format($row->expense_details()->sum(DB::raw('price * quantity')), 0, ', ', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


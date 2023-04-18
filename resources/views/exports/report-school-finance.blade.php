<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Dana Dari</th>
            <th>Dana</th>
            <th>Tipe Uang</th>
            <th>Note</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($walletDetail as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->wallet->name }}</td>
                <td>{{ $row->amount }}</td>
                <td>{{ $row->cashflow_type == 'in' ? 'Masuk' : 'Keluar' }}</td>
                <td>{{ $row->note }}</td>
                <td>{{ $row?->created_at->format('m-d-Y') }}</td>

            </tr>
        @endforeach
    </tbody>
</table>

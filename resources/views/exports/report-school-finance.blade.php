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
        @foreach ($WalletLog as $row)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $row->wallet->name }}</td>
                <td>{{ $row->amount }}</td>
                <td>{{ match ($row->cashflow_type) {
                    App\Models\WalletLog::CASHFLOW_TYPE_IN => 'Masuk',
                    App\Models\WalletLog::CASHFLOW_TYPE_OUT => 'Keluar',
                    App\Models\WalletLog::CASHFLOW_TYPE_INIT => 'Saldo Awal',
                } }}
                </td>
                <td>{{ $row->note }}</td>
                <td>{{ $row?->created_at->format('Y M d H:i') }}</td>

            </tr>
        @endforeach
    </tbody>
</table>

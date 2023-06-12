<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Siswa</th>
            <th>Tahun Ajaran</th>
            <th>Tingkatan</th>
            <th>Kelas</th>
            <th>Tipe Uang Sekolah</th>
            <th>Tipe Pembayaran</th>
            <th>Total Bayar</th>
            <th>Sisa Bayar</th>
            <th>Status</th>
            <th>Tanggal Invoice</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($studentTuitions as $row)
            <tr>
                @php
                    $tuition = $row->student_tuition_details->first();
                    $tuitionType = $row->student_tuition_details
                        ->map(function ($row) {
                            return $row->tuition->tuition_type->name;
                        })
                        ->implode(',');

                    $remainingDebt = $row->grand_total - $row->student_tuition_payment_histories->sum('price');
                @endphp

                <td>{{ $row->bill_number }}</td>
                <td>{{ $row->student->name }}</td>
                <td>{{ $tuition->tuition->academic_year->academic_year_name }}</td>
                <td>{{ $tuition->tuition->grade->grade_name }}</td>
                <td>{{ $row->student->classrooms->first()->name }}</td>
                <td>{{ $tuitionType }}</td>
                <td>
                    @php
                    $res = '';
                        foreach ($row->student_tuition_payment_histories as $histori) {
                            $res .= $histori->payment_type->name . ' (Rp ' . number_format($histori->price, 0, ',', '.') . '), ';
                        }
                    @endphp
                    {{-- {{ $row->payment_type?->name }} --}}
                    {{ $res }}
                </td>
                <td>{{ $row->grand_total }}</td>
                <td>{{ $remainingDebt > 0 ? $remainingDebt : 0 }}</td>
                <td>{{ $statusPayment($row->status) }}</td>
                <td>{{ $row->created_at->format('d F Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

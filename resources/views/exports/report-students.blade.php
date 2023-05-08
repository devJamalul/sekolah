<table width="100%" border="1">
    <thead>
        <tr align="center">
            <th width="10%"><strong>No</strong></th>
            <th width="30%"><strong>Nama</strong></th>
            <th width="20%"><strong>Jenis Kelamin</strong></th>
            <th width="30%"><strong>Email</strong></th>
            <th width="10%"><strong>Tingkatan - Kelas</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $index => $row)
            <tr align="center">
                <th>{{ $index+1 }}</th>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['gender'] == 'L' ? "Laki-Laki" : "Perempuan" }}</td>
                <td>{{ $row['email'] }}</td>

                @if (isset($row['classrooms']))
                    <td>{{ $row['classrooms'][0]['grade']['grade_name'] }} - {{ $row['classrooms'][0]['name'] }} </td>
                @else
                    <td>Belum Mendapat Kelas</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>


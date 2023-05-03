<table>
  <thead>
      <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Tahun Ajaran</th>
          <th>Tingkatan</th>
          <th>Nama Kelas</th>
      </tr>
  </thead>
  <tbody>
      @foreach ($students as $index => $row)
          <tr>
              <td>{{ $index+1 }}</td>
              <td>{{ $row->name }}</td>
              {{-- <td>{{ $row?->created_at->format('m-d-Y') }}</td> --}}
          </tr>
      @endforeach
  </tbody>
</table>

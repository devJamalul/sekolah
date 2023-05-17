<div class="btn-group">
    <div class="dropdown">
        <button class="btn btn-primary btn-sm dropdown-toggle shadow-sm" type="button" id="dropdownMenuButton"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Opsi
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="#" data-btn="Simpan" data-label="{{ 'Tetapkan Wali Kelas ' }}"
                data-staff-id='{{ $row->id }}' data-classroom-id=""
                data-url="{{ route('assign-classroom-staff.store') }}" onclick="modalAssignClass(this)">Tetapkan</a>
            @if ($classroom)
                <a class="dropdown-item" href="#" data-btn="Ubah"
                    data-label="{{ 'Ubah Wali Kelas Dari Kelas ' . $classroom->name }}"
                    data-staff-id='{{ $row->id }}'data-url="{{ route('assign-classroom-staff.destroy') }}"
                    data-classroom-id='{{ $classroom->id }}' onclick="modalAssignClass(this)">Ubah</a>
            @endif
        </div>
    </div>
</div>

@extends('layout.master-page')

@section('title', $title)

@section('content')

  <div class="col-lg-6">

    {{-- Header --}}
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>
        <div>
            <a href="{{ route('tuition-master.index', ['id' => $id]) }}" class="btn btn-primary btn-sm mr-2">Kembali</a>
        </div>
    </div>
    {{-- End Header --}}

    {{-- Content --}}
    <div class="card">
      <div class="card-body">
        <form action="{{ route('tuition-master.store', ['id' => $id]) }}" method="post">
          @csrf
          <input type="text" name="student_id" value="{{ $id }}" hidden>
          <div class="form-group">
            <label for="tuition_id">Biaya Sekolah<span class="text-small text-danger">*</span></label>
            <select id="tuition_id" name="tuition_id"
                class="form-control select2 @error('tuition_id') is-invalid @enderror" required>
                <option value="">--- Pilih Biaya Sekolah ---</option>
                @foreach ($tuitions as $tuition)
                    <option value="{{ $tuition->id }}" @selected(old('tuition_id') == $tuition->id)>
                        {{ $tuition->tuition_type->name }} - {{ $tuition->grade->grade_name }}
                    </option>
                @endforeach
            </select>
            @error('tuition_id')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form-group">
            <label for="price">Harga<span class="text-small text-danger">*</span></label>
            <input type="text" id="price" name="price" value="{{ old('price', ) }}" id="price" class="form-control @error('price') is-invalid @enderror" required>
            @error('price')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form-group">
            <label for="note">Catatan</label>
            <textarea name="note" id="note" rows="4" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
            @error('note')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
    {{-- End Content --}}

  </div>

@endsection
@push('js')
  <script>
    formatAngka('#price')
  </script>
@endpush
@extends('layout.master-page')

@section('content')
    {{-- start ROW --}}

    <div class="row">

        {{-- start table academy years --}}
        <div class="col-lg-6">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('config.save') }}" method="post">
                        @csrf
                        @foreach ($configs as $key => $config)
                            <input type="hidden" name="config[]" value="{{ $config->getKey() }}">
                            <div class="form-group">
                                <label for="config-school-input">{{ $config->name }}</label>
                                <input type="text" class="form-control @error('value.' . $key) is-invalid @enderror"
                                    name="value[]" value="{{ old('value.' . $key, schoolConfig($config->getKey())) }}"
                                    id="config-school-input">
                                @error('value.' . $key)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- END table academy years --}}
    </div>
    {{-- END ROW --}}
@endsection

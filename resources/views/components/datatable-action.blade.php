@php
    $tombolUbah = "<a class=\"dropdown-item\" href=\"$edit_url\">Ubah</a>";
    $tombolHapus = "<a class=\"dropdown-item\" data-url=\"$delete_url\" data-redirect=$redirect_url onclick=\"softDelete(this)\">Hapus</a>";
@endphp
<div class="btn-group">
  <div class="dropdown">
    <button class="btn btn-primary btn-sm dropdown-toggle shadow-sm" type="button" id="dropdownMenuButton"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Opsi
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      @if (isset($resource))
        @can("$resource.edit")
          {!! $tombolUbah !!}
        @endcan
      @else
        {!! $tombolUbah !!}
      @endif
      @if (isset($resource))
        @can("$resource.destroy")
          {!! $tombolHapus !!}
        @endcan
      @else
        {!! $tombolHapus !!}
      @endif

      @if (isset($custom_links))
            @foreach ($custom_links as $link)
            <a class="dropdown-item" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
            @endforeach
      @endif

      @stack('item')

    </div>
  </div>
  @stack('opsi')
</div>
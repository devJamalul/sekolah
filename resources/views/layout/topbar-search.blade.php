@hasanyrole('super admin|ops admin')
    @if ((!Request::is('schools') and !Request::is('schools/*')) and (!Request::is('master-configs') and !Request::is('master-configs/*')))
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <select id="school_selector" name="school_selector"
                class="form-control select2 @error('school_selector') is-invalid @enderror" required>
                <option value="">Pilih Sekolah ...</option>
                @foreach ($school_selectors as $school_selector)
                    <option value="{{ $school_selector->getKey() }}" @selected($school_selector->getKey() == session('school_id'))>
                        {{ $school_selector->school_name }}
                    </option>
                @endforeach
            </select>
        </form>
        @push('js')
            <script>
                $(function() {
                    $('#school_selector').change(function() {
                        const token = $('meta[name="csrf-token"]').attr('content');
                        const data = {
                            _token: token,
                            school_selector: $(this).val()
                        };
                        fetch(route('school_selector'), {
                                method: "POST", // or 'PUT'
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify(data),
                            })
                            .then((response) => response.json())
                            .then((data) => {
                                console.log("Success:", data);
                                window.location.reload()
                            })
                            .catch((error) => {
                                console.error("Error:", error);
                                window.location.reload()
                            });
                    });
                });
            </script>
        @endpush
    @endif
@endrole

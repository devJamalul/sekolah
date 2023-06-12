@include('sweetalert::alert')

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/autoNumeric.js') }}"></script>
<script src="{{ asset('js/bs-custom-file-input.min.js') }}"></script>

<script>
    $("form").attr('autocomplete', 'off')
    $("input").attr('autocomplete', false)
    $(".select2").select2({
        theme: "bootstrap",
        placeholder: function() {
            $(this).data('placeholder');
        }
    })
    $(document).ready(function() {
        bsCustomFileInput.init()
    })
</script>

@stack('js')

{{-- Vendor Scripts --}}
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
<script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/metrics.js" defer></script>
<script src="https://cdn.jsdelivr.net/gh/underground-works/clockwork-browser@1/dist/toolbar.js" defer></script>
@yield('vendor-script')
{{-- Theme Scripts --}}
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>
@if($configData['blankPage'] === false)
<script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
@endif
{{-- Data Table Scripts --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}" ></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap4.js')) }}" ></script>
{{-- <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script> --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
{{-- <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script> --}}
<script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}" ></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}" ></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
{{-- <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script> --}}
{{-- Data time picker --}}
{{-- <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script> --}}
{{-- <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script> --}}
{{-- ToastR --}}
{{-- <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script> --}}
{{-- <script src="{{ asset(mix('js/scripts/extensions/ext-component-toastr.js')) }}"></script> --}}

{{-- Prevent double submit on forms --}}
<script>
    $('form').submit(function(){
        $('input[type=submit]', this).attr('disabled', 'disabled');
    });

    $('.dt-button').on('click', function(event) {
        $('.dt-button').disabled = true;
    });

    $(document).on("submit", "form", function(event)
    {
        $(this).find("input[type=submit], button").each(function()
        {
            var $button = $(this);

            if ($button.attr("disabled"))
                return; // this button is already disabled, do not touch it

            setTimeout(function()
            {
                $button.attr("disabled", "disabled");
                $button.addClass("submitting");
                setTimeout(function()
                {
                    $button.removeAttr("disabled");
                    $button.removeClass("submitting");
                }, 10000); // remove disabled status after timeout (ms)
            }, 0); // let the event loop run before disabling the buttons to allow form submission to collect form data (and emit "formdata" event) before disabling any buttons and hope that user is not fast enough to double submit before this happens
        });
    });
</script>
{{-- End prevent double click --}}

{{-- page script --}}
@yield('page-script')
@yield('page-script2')
{{-- page script --}}

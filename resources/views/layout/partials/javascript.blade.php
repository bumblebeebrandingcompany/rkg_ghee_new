{{-- app --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js?v={{$asset_v}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js?v={{$asset_v}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js?v={{$asset_v}}"></script>
{{-- vendor --}}
<script src="{{ asset('assets/plugins/global/plugins.bundle.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('assets/js/pdfobject.js?v=' . $asset_v) }}"></script>

<script src="{{ asset('plugins/dropzone/dropzone.init.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('/assets/plugins/jquery.validate/jquery.validate.min.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('plugins/jquery-validation/dist/additional-methods.min.js?v=' . $asset_v) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js?v={{$asset_v}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js?v={{$asset_v}}"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js?v={{$asset_v}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js?v={{$asset_v}}"></script>
<script src="{{ asset('plugins/tinymce/tinymce.bundle.js?v=' . $asset_v) }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        jQuery.extend($.fn.dataTable.defaults, {
            fixedHeader: false,
            aLengthMenu: [[25, 50, 100, 200, 500, 1000, -1], [25, 50, 100, 200, 500, 1000, 'All']],
            iDisplayLength: 25,
            dom: 'lBfrtip',
            buttons: [
                //'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'colvis',
                'pdf'
            ]
        });

        jQuery.validator.setDefaults({
            invalidHandler: function() {
                toastr.error("Some error in input fields");
            }
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            beforeSend: function(jqXHR, settings) {
                if (settings.url.indexOf('http') === -1) {
                    settings.url = APP.APP_URL + settings.url;
                }
            }
        });

        if ($('input#toastr_status').length) {
            let status = $('input#toastr_status').attr('data-status');
            let msg = $('input#toastr_status').attr('data-msg');
            if (status === '1' && msg.length) {
                toastr.success(msg);
            } else if ((status == '' || status === '0') && msg.length) {
                toastr.error(msg);
            }
        }

        $("body").tooltip({ selector: '[data-toggle=tooltip]' });

        //log out user on click of log out btn
        $(document).on('click', '#log-me-out', function() {
            $("form#logout-form").submit();
        });

        //validate if entered input is number only
        $(document).on('keypress', 'input.input_number', function(event) {
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!/^\d*$/.test(key)) {
                event.preventDefault();
                return false;
            }
        });

        //common configuration : tinyMCE editor
        // tinymce.overrideDefaults({
        //     height: 200,
        //     theme: 'silver',
        //     plugins: [
        //     'pagebreak',
        //     'wordcount fullscreen nonbreaking',
        //     'paste help'
        //     ],
        //     toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |' +
        //     ' bullist numlist outdent indent preview fullpage | ' +
        //     'forecolor backcolor',
        //     menubar: 'favs edit view format help'
        // });

        $('.menu_href').click(function(){
            window.location = $(this).data('href');
        });

        
        
        $('.decrease_qty').click(function(){
            qty_element = $(this).closest('.input-group').find('.quantity');
            
            quantity = qty_element.val();
            if(quantity == ''){
                quantity = 0;
            }
            quantity = parseInt(quantity);

            if(quantity !=0){
                qty_element.val(quantity - 1);
            }
        });
        $('.increase_qty').click(function(){
            qty_element = $(this).closest('.input-group').find('.quantity');

            quantity = qty_element.val();
            if(quantity == ''){
                quantity = 0;
            }
            quantity = parseInt(quantity);

            qty_element.val(quantity + 1);
        });

        $('button.save_order_draft').click(function(){
            if (confirm("Are you sure to save order as draft?") == true) {
                return true;
            } else {
                return false;
            }
        });
    });
</script>
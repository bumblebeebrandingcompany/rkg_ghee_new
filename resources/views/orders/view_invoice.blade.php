<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">View invoice</h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                <span class="svg-icon svg-icon-2x"></span>
            </div>
            <!--end::Close-->
        </div>

        <div class="modal-body">
            <div id="example1" style="height: 70vh"></div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <a href="{{route('download.invoice', ['id' => $order->id])}}" class="btn btn-primary btn-hover-scale">Download</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    PDFObject.embed("{{Storage::url($order->invoice_file_name)}}", "#example1");
</script>
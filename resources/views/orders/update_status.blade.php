<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Status - <code>{{$order->reference_id}}</code></h3>

            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                <span class="svg-icon svg-icon-2x"></span>
            </div>
            <!--end::Close-->
        </div>

        <form method="POST" action="{{route('admin.orders.update_status', $order->id)}}"enctype='multipart/form-data'>
                @csrf
            <div class="modal-body">
                <table class="table"> 
                    <tr>
                        <td class="col-md-6">Order Details</td>
                        <td class="col-md-6">Distributor</td>
                    </tr>
                    <tr>
                        <td>Sales Rep.</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>
                            <div class="">
                                <label for="invoice_no" class="form-label" id="invoice_no_label">Invoice No.</label>
                                <input type="text" name="invoice_no" class="form-control form-control-solid" id="invoice_no" 
                                    value="{{$order->invoice_no}}" />
                            </div>
                        </td>
                        <td>
                            <div class="">
                                <label for="order_status" class="required form-label">Status</label>
                                <select id="order_status" required class="form-select" name="order_status">
                                    @foreach($order_statuses as $k => $status)
                                        <option value="{{$k}}" 
                                            @if($order->order_status == $k) selected @endif

                                            @if(empty($order->invoice_no) && in_array($k, ['order_dispatched', 'order_delivered'])) disabled @endif 
                                           
                                            @if(!empty($order->sub_stockist_id) && in_array($k, ['draft'])) disabled @endif

                                            @if(empty($order->sub_stockist_id) && in_array($k, ['draft_by_sub_stockist', 'pending_for_super_stockist'])) disabled @endif


                                        >
                                            {{$status['label']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="">
                                <label for="invoice_file" class="form-label" id="invoice_file_label">Invoice File(PDF)</label>
                                <input type="file" name="invoice_file" class="form-control form-control-solid" id="invoice_file"
                                accept="application/pdf"/>

                                @if(!empty($order->invoice_file_name))
                                    <div class="form-text">Invoice already exist. If you upload new invoice previous invoice will replaced.</div>
                                @endif

                            </div>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-hover-scale">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $('select#order_status').change(function(){

        if($(this).val() == 'order_invoiced'){
            $('#invoice_no').attr("required", "required");
            $('#invoice_file').attr("required", "required");

            $('#invoice_no_label').addClass('required');
            $('#invoice_file_label').addClass('required');
        } else {
            $('#invoice_no').removeAttr("required");
            $('#invoice_file').removeAttr("required");

            $('#invoice_no_label').removeClass('required');
            $('#invoice_file_label').removeClass('required');
        }
    });
</script>
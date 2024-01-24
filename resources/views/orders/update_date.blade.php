<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Update Date</h3>
             <code>
                Updating date will change the date of order, date of points & the price of products will be changed as per the date.</code>
            <!--begin::Close-->
            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                <span class="svg-icon svg-icon-2x"></span>
            </div>
            <!--end::Close-->
        </div>
        <form method="POST" action="{{ route('admin.orders.update_date', $order->id) }}"enctype='multipart/form-data'>
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label id="date" class="control-label">Date</label>
                    <div>
                        <input type="date" id="date" value="{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}" class="form-control input-lg" name="date" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-hover-scale">Update Date</button>
            </div>
        </form>
    </div>
</div>

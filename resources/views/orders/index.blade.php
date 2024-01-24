@extends('layout.app')
@section('content')

    <div class="row g-5 g-xl-12 mt-1">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-12">
                <div class="card-header border-0 pt-5">
                    <h1 class="card-title align-items-start flex-column">
                        @if (isset($ordertype) && $ordertype == 'super_stockist')
                            <span class="card-label fw-bolder fs-3 mb-1">All Sub Stockist Orders</span>
                        @else
                            <span class="card-label fw-bolder fs-3 mb-1">All Orders</span>
                        @endif
                        @if (auth()->user()->role == 'super_admin')
                        <small><code>Points are added on order status <b>Dispatched or Order Delivered.</b> </code>
                        </small> <br>
                        <small>
                            <code>Points date will be on the date of <b>order placed.</b> </code>
                        </small> 
                        @endif
                    </h1>

                    @if (in_array(auth()->user()->role, ['distributor']) && !isset($ordertype))
                        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                            title="" data-bs-original-title="Click for new order">
                            <a href="{{ route('dist.orders.create') }}" class="btn btn-sm btn-light btn-active-primary">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                            rx="1" transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                            fill="black"></rect>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                New Order
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-body py-3">
                    <div class="row">
                        @if (auth()->user()->role != 'distributor' &&
                                auth()->user()->role != 'wholesaler' &&
                                auth()->user()->role != 'sub_stockist' &&
                                auth()->user()->role != 'super_stockist' &&
                                !isset($_GET['ordertype']))

                            @if (auth()->user()->role != 'area_manager')
                                <div class="col-md-3 mb-10">
                                    <label for="area_manager" class="form-label">
                                        Area Manager
                                    </label>
                                    <select id="area_manager" class="form-select" name="area_manager">
                                        <option value="">
                                            All
                                        </option>
                                        @foreach ($area_manager as $area_manager_id => $area_manager)
                                            <option value="{{ $area_manager_id }}">
                                                {{ $area_manager }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-3 mb-10">
                                <label for="sales_rep" class="form-label">
                                    Sales Rep
                                </label>
                                <select id="sales_rep" class="form-select" name="sales_rep">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($sales_rep as $sales_rep_id => $sale)
                                        <option value="{{ $sales_rep_id }}">
                                            {{ $sale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="status" class="form-label">
                                    Status
                                </label>
                                <select id="status" class="form-select" name="status">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($order_statuses as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="distributor" class="form-label">
                                    Distributor
                                </label>
                                <select id="distributor" class="form-control" name="distributor">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($distributors as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="wholesaler" class="form-label">
                                    Wholesaler
                                </label>
                                <select id="wholesaler" class="form-select" name="wholesaler">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($wholesaler as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="state" class="form-label">
                                    State
                                </label>
                                <select id="state" class="form-select" name="state">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($states as $key => $state)
                                        <option value="{{ $state }}">
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- @if (in_array(auth()->user()->role, ['super_admin']))
                                <div class="col-md-3 mb-10">
                                    <label for="distributor" class="form-label">
                                        Distributor
                                    </label>
                                    <select id="distributor" class="form-select" name="distributor">
                                        <option value="">
                                            All
                                        </option>
                                        @foreach ($distributors as $id => $name)
                                            <option value="{{ $id }}">
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif --}}
                        @endif

                        @if (auth()->user()->role == 'super_admin' && $ordertype == 'edit_date')
                            <div class="col-md-3 mb-10">
                                <label for="area_manager" class="form-label">
                                    Area Manager
                                </label>
                                <select id="area_manager" class="form-select" name="area_manager">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($area_manager as $area_manager_id => $area_manager)
                                        <option value="{{ $area_manager_id }}">
                                            {{ $area_manager }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-10">
                                <label for="sales_rep" class="form-label">
                                    Sales Rep
                                </label>
                                <select id="sales_rep" class="form-select" name="sales_rep">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($sales_rep as $sales_rep_id => $sale)
                                        <option value="{{ $sales_rep_id }}">
                                            {{ $sale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="status" class="form-label">
                                    Status
                                </label>
                                <select id="status" class="form-select" name="status">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($order_statuses as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="distributor" class="form-label">
                                    Distributor
                                </label>
                                <select id="distributor" class="form-control" name="distributor">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($distributors as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="wholesaler" class="form-label">
                                    Wholesaler
                                </label>
                                <select id="wholesaler" class="form-select" name="wholesaler">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($wholesaler as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-10">
                                <label for="state" class="form-label">
                                    State
                                </label>
                                <select id="state" class="form-select" name="state">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($states as $key => $state)
                                        <option value="{{ $state }}">
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if (auth()->user()->role != 'sub_stockist' && $ordertype == 'super_stockist')
                            <div class="col-md-3 mb-10">
                                <label for="status" class="form-label">
                                    Status
                                </label>
                                <select id="status" class="form-select" name="status">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($order_statuses as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if (auth()->user()->role != 'sub_stockist' && $ordertype == 'super_stockist')
                            <div class="col-md-3 mb-10">
                                <label for="sub_stockist" class="form-label">
                                    Sub stockist
                                </label>
                                <select id="sub_stockist" class="form-select" name="sub_stockist">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($sub_stockists as $id => $company_name)
                                        <option value="{{ $id }}">
                                            {{ $company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif


                        <div class="col-md-3">
                            <label for="created_at" class="form-label">
                                Created at
                            </label>
                            <input type="text" class="form-control form-control-solid" name="created_at"
                                id="created_at" readonly>
                        </div>
                    </div>


                    <div class="row">
                        <div class="table-responsive">

                            <table class="table table-rounded table-striped border gy-7 gs-7" id="orders_table">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Action</th>
                                        <th>Invoice No.</th>
                                        <th>Reference id</th>
                                        <th>Status</th>
                                        <th>Company Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Sales Rep.</th>
                                        <th>Total weight</th>
                                        @if (auth()->user()->role != 'sub_stockist' && $ordertype != 'super_stockist')
                                            <th>Total amount</th>
                                        @endif
                                    @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                                            <th>Points Earned</th>
                                    @endif
                                        <th>Created at</th>
                                    </tr>
                                </thead>

                                @if (auth()->user()->role != 'sub_stockist' && Auth::user()->role != 'sub_stockist' && $ordertype != 'super_stockist')
                                    <tfoot>
                                        <tr>
                                            <td colspan="8" class="text-center font-weight-bold"> Total </td>
                                            <!--  <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td> -->
                                            <td class="footer_total_weight"></td>
                                            <td class="footer_total_amount"></td>
                                                @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                                                 <td class="footer_total_points"></td>
                                                @endif
                                        </tr>

                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            var currentDate = new Date();

            // Determine the start date based on the current month
            var startYear = currentDate.getFullYear();
            if (currentDate.getMonth() < 2) {
                startYear -= 1;
            }
            var startDate = new Date(startYear, 3, 1);

            // Determine the end date based on the current month
            var endYear = currentDate.getFullYear();

            if (currentDate.getMonth() > 2) {
                endYear += 1;
            }
            var endtDate = new Date(endYear, 2, 31);

            //initialize date range
            const __dateRanges = {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ],
                'This Year': [startDate, endtDate],
                'Last Year': [new Date(startYear - 1, 3, 1), new Date(endYear - 1, 2, 31)],
            };

            $('#created_at').daterangepicker({
                startDate: startDate,
                endDate: moment(),
                ranges: __dateRanges
            });

            var orders_table = $('#orders_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/dist/orders',
                    data: function(d) {


                        @if (isset($ordertype))
                            d.ordertype = '{{ $ordertype }}';
                        @endif
                        d.status = $("#status").val();
                        d.distributor = $("#distributor").val();
                        d.wholesaler = $("#wholesaler").val();
                        d.sales_rep = $("#sales_rep").val();
                        d.sub_stockist = $("#sub_stockist").val();
                        d.area_manager = $("#area_manager").val();
                        d.state = $('#state').val();
                        d.start_date = $('#created_at')
                            .data('daterangepicker').startDate.format('YYYY-MM-DD');
                        d.end_date = $('#created_at')
                            .data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
                },

                @if (auth()->user()->role != 'sub_stockist' && $ordertype != 'super_stockist')
                    @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                        order: [
                                [11, "desc"]
                            ],
                    @else
                        order: [
                                [10, "desc"]
                            ],
                    @endif  
                
                @else
                @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                        order: [
                                [10, "desc"]
                            ],
                    @else
                        order: [
                                [9, "desc"]
                            ],
                    @endif
                @endif

                columnDefs: [{
                    targets: [0],
                    orderable: false,
                    searchable: false,
                }, ],

                columns: [{
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'reference_id',
                        name: 'reference_id'
                    },
                    {
                        data: 'order_status',
                        name: 'order_status'
                    },
                    {
                        data: 'distributor_name',
                        name: 'distributor_name',
                        searchable: false,

                    },
                    {
                        data: 'distributor_city',
                        name: 'distributor.address_city'
                    },
                    {
                        data: 'distributor_state',
                        name: 'distributor.address_state'
                    },
                    {
                        data: 'sales_rep_name',
                        name: 'sales_rep.name'
                    },
                    {
                        data: 'total_weight',
                        name: 'total_weight'
                    },


                    @if (auth()->user()->role != 'sub_stockist' && $ordertype != 'super_stockist')
                        {
                            data: 'total_price',
                            name: 'total_price',
                            orderable: true,
                            searchable: false,
                        },
                    @endif
                    @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                        {
                            data: 'points_earned',
                            name: 'points_earned',
                            searchable: false,
                        },
                    @endif
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                ],

                @if (auth()->user()->role != 'sub_stockist' && $ordertype != 'super_stockist')
                    "footerCallback": function(row, data, start, end, display) {
                        var total_amount = 0;
                        var total_points = 0;
                        var total_weight = 0;

                        for (var r in data) {

                            if (data[r].status_for_calculate != 'order_cancelled' && data[r]
                                .status_for_calculate != 'draft' && data[r].status_for_calculate !=
                                'draft_by_sub_stockist' && data[r].status_for_calculate !=
                                'pending_for_super_stockist') {
                                total_amount = total_amount + parseFloat(data[r].price_for_calculate);
                                total_weight = total_weight + parseFloat(data[r]
                                    .total_weight_for_calculate);

                            }

                            total_points = total_points + parseFloat(data[r].total_points);
                            console.log(data[r].total_weight_for_calculate);

                            // total_amount += $(data[r].total_price).data('orig-value') ? 
                            // parseFloat($(data[r].total_price).data('orig-value')) : 0;

                        }

                        console.log(addCommas(total_weight.toFixed(2)))
                        $('.footer_total_amount').html('Rs. ' + addCommas(total_amount.toFixed()));
                        $('.footer_total_points').html(addCommas(total_points.toFixed()) + ' Points');
                        $('.footer_total_weight').html(total_weight.toFixed(3) + ' Ton(s)');

                    },
                @endif

            });

            $(document).on('change',
                '#status, #distributor, #created_at, #sales_rep, #wholesaler, #sub_stockist, #state',
                function() {
                    // console.log($('#state').val())
                    orders_table.ajax.reload();
                });

            $(document).on('change', '#area_manager', function() {
                $.ajax({
                    type: 'get',
                    url: "{{ route('admin.dist_sales_under_am') }}",
                    data: {
                        id: $('#area_manager').val()
                    },
                    success: function(res) {
                        console.log(res.sales.length);
                        html_dist = '<option value="">All</option>'
                        for (var i = 0; i < res.disp.length; i++) {
                            html_dist = html_dist + '<option value=' + res.disp[i]['id'] + '>' +
                                res.disp[i]['company_name'] + '</option>';
                        }
                        html_sales = '<option value="">All</option>'
                        for (var i = 0; i < res.sales.length; i++) {
                            html_sales = html_sales + '<option value=' + res.sales[i]['id'] +
                                '>' + res.sales[i]['name'] + '</option>';
                        }
                        html_wholesaler = '<option value="">All</option>'
                        for (var i = 0; i < res.wholesaler.length; i++) {
                            html_wholesaler = html_wholesaler + '<option value=' + res
                                .wholesaler[i]['id'] + '>' + res.wholesaler[i]['company_name'] +
                                '</option>';
                        }

                        $('#distributor').html(html_dist);
                        $('#sales_rep').html(html_sales);
                        $('#wholesaler').html(html_wholesaler);
                    },
                    error: function() {
                        console.log('error');
                    }
                });
                orders_table.ajax.reload();
            });

            $(document).on('click', 'a.update_status_link', function() {
                $.ajax({
                    method: 'GET',
                    url: $(this).data('href'),
                    dataType: 'html',
                    success: function(result) {
                        if (result) {
                            $('div#global-modal').html(result);

                            var myModal = new bootstrap.Modal(document.getElementById(
                                'global-modal'), {
                                keyboard: false
                            });
                            myModal.show();
                        }
                    },
                });
            });

            $(document).on('click', 'a.update_date_link', function() {
                $.ajax({
                    method: 'GET',
                    url: $(this).data('href'),
                    dataType: 'html',
                    success: function(result) {
                        if (result) {
                            $('div#global-modal').html(result);

                            var myModal = new bootstrap.Modal(document.getElementById(
                                'global-modal'), {
                                keyboard: false
                            });
                            myModal.show();
                        }
                    },
                });
            });


            $(document).on('click', 'button.view_invoice', function() {
                $.ajax({
                    method: 'GET',
                    url: $(this).data('href'),
                    dataType: 'html',
                    success: function(result) {
                        if (result) {
                            $('div#global-modal').html(result);

                            var myModal = new bootstrap.Modal(document.getElementById(
                                'global-modal'), {
                                keyboard: false
                            });
                            myModal.show();
                        }
                    },
                });
            });

            //Delete order
            $(document).on('click', 'button.delete_order', function() {
                if (confirm("@lang('messages.are_you_sure')")) {
                    $.ajax({
                        method: 'DELETE',
                        url: $(this).data('href'),
                        dataType: 'json',
                        success: function(result) {
                            alert(result.msg);
                            orders_table.ajax.reload();
                        }
                    });
                }
            });

            //cancel order
            $(document).on('click', 'button.cancel_order', function() {
                if (confirm("@lang('messages.are_you_sure')")) {
                    $.ajax({
                        method: 'POST',
                        url: $(this).data('href'),
                        dataType: 'json',
                        success: function(result) {
                            alert(result.msg);
                            orders_table.ajax.reload();
                        }
                    });
                }
            });

            $(document).on('click', 'button.forward', function() {
                if (confirm("@lang('messages.are_you_sure')")) {
                    $.ajax({
                        method: 'POST',
                        url: $(this).data('href'),
                        dataType: 'json',
                        success: function(result) {
                            alert(result.msg);
                            orders_table.ajax.reload();
                        }
                    });
                }
            });


            function addCommas(nStr) {
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                return x1 + x2;
            }

        });
    </script>
    <script>
        $('#distributor').select2();
        $('.select2-selection').height('20');
    </script>
@endsection

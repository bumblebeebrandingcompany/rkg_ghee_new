@extends('layout.app')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Filter Products Report</h1>
            </div>
            <div class="card-body">
                <form class="row" id="" method="GET" action="{{ route('admin.product_report') }}">
                    <div class="col-md-3 mb-10">
                        <label for="performing" class="required form-label">Performing</label>
                        <select id="performing" required class="form-select" name="performing">
                            <option selected value="">Choose...</option>
                            <option
                                @if (isset($_GET['performing'])) @if ($_GET['performing'] == 'best')
                                    selected @endif
                                @endif value="best">Best</option>
                            <option
                                @if (isset($_GET['performing'])) @if ($_GET['performing'] == 'worst')
                                selected @endif
                                @endif value="worst">Worst</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-10">
                        <label for="order_by" class="required form-label">Ordering by</label>
                        <select id="order_by" required required class="form-select" name="order_by">
                            <option value="" selected>Choose...</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'box')
                                selected @endif
                                @endif value="box">Boxs</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'tonnage')
                                selected @endif
                                @endif value="tonnage">Tonnage</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'order_value')
                                selected @endif
                                @endif value="order_value">Order value</option>
                        </select>
                    </div>
                    <div class="mt-8 col-md-3">
                        <button type="submit" id="user_sub_btn" class="btn btn-primary ">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (isset($_GET['performing']) && isset($_GET['order_by']))
        <div class="row mt-10">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <h1 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Products Report
                            </span>
                        </h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
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

                            <div class="col-md-3 mb-10">
                                <label for="sku_list" class="form-label">
                                    SKU Series
                                </label>
                                <select id="sku_list" class="form-select" name="sku_list">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($sku_list as $sku)
                                        <option value="{{ $sku }}">
                                            {{ $sku }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            
                            <div class="col-md-3">
                                <label for="daterange" class="form-label">
                                    Date Range
                                </label>
                                <input type="text" class="form-control form-control-solid" name="daterange"
                                    id="daterange" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-rounded table-striped border gy-7 gs-7" id="sales_list_table">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Boxes</th>
                                            <th>Total Tonnage ordered</th>
                                            <th>Order Value</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@if (isset($_GET['performing']) && isset($_GET['order_by']))
    @section('javascript')
        <script>
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

                $('#daterange').daterangepicker({
                    startDate: startDate,
                    endDate: moment(),
                    ranges: __dateRanges
                });

                var sales_list_table = $('#sales_list_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/admin/product/report',
                        data: function(d) {
                            d.state = $('#state').val();
                            d.sku_list = $('#sku_list').val();
                            d.start_date = $('#daterange')
                                .data('daterangepicker').startDate.format('YYYY-MM-DD');
                            d.end_date = $('#daterange')
                                .data('daterangepicker').endDate.format('YYYY-MM-DD');
                        }
                    },
                    @if ($_GET['order_by'] == 'box')
                        @if ($_GET['performing'] == 'best')
                            order: [
                                [2, "desc"]
                            ],
                        @elseif ($_GET['performing'] == 'worst')
                            order: [
                                    [2, "asc"]
                                ],
                        @endif
                    @elseif ($_GET['order_by'] == 'tonnage')
                        @if ($_GET['performing'] == 'best')
                            order: [
                                [3, "desc"]
                            ],
                        @elseif ($_GET['performing'] == 'worst')
                            order: [
                                    [3, "asc"]
                                ],
                        @endif
                    @elseif ($_GET['order_by'] == 'order_value')
                        @if ($_GET['performing'] == 'best')
                            order: [
                                [4, "desc"]
                            ],
                        @elseif ($_GET['performing'] == 'worst')
                            order: [
                                    [4, "asc"]
                                ],
                        @endif
                    @endif





                    columns: [{
                            data: 'product_type',
                            name: 'product_type'
                        },
                        {
                            data: 'barcodes',
                            name: 'barcodes',
                            searchable: false,
                        },
                        {
                            data: 'boxes',
                            name: 'boxes',
                            searchable: false,
                        },
                        {
                            data: 'total_volumn',
                            name: 'total_volumn',
                            searchable: false,
                        },
                        {
                            data: 'total_value',
                            name: 'total_value',
                            searchable: false,
                        }
                    ],
                });
                $(document).on('change', '#state, #sku_list, #daterange', function() {
                    // console.log($('#state').val())
                    sales_list_table.ajax.reload();
                });
            });
        </script>
    @endsection
@endif

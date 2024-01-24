@extends('layout.app')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Filter Users Report</h1>
            </div>
            <div class="card-body">
                <form class="row" id="" method="GET" action="{{ route('admin.report') }}">
                    <div class="col-md-3 mb-10">
                        <label for="role" class="required form-label">Role</label>
                        <select id="role" required class="form-select" name="role">
                            <option value="" selected>Choose...</option>
                            <option
                                @if (isset($_GET['role'])) @if ($_GET['role'] == 'distributor')
                                selected @endif
                                @endif value="distributor">Distributor</option>
                            <option
                                @if (isset($_GET['role'])) @if ($_GET['role'] == 'wholesaler')
                            selected @endif
                                @endif value="wholesaler">Wholesaler</option>
                            <option
                                @if (isset($_GET['role'])) @if ($_GET['role'] == 'sub_stockist')
                            selected @endif
                                @endif value="sub_stockist">Sub stockists</option>
                            <option
                                @if (isset($_GET['role'])) @if ($_GET['role'] == 'sales_rep')
                            selected @endif
                                @endif value="sales_rep">Sales Rep</option>
                        </select>
                    </div>
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
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points')
                                selected @endif
                                @endif value="points">Points</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'target_acheived')
                            selected @endif
                                @endif value="target_acheived">Target Acheived</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'percentage_of_acheivement')
                            selected @endif
                                @endif value="percentage_of_acheivement">% of Acheivement</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards')
                            selected @endif
                                @endif value="rewards">Reward Value</option>
                            <option
                                @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'total_order_value')
                            selected @endif
                                @endif value="total_order_value">Total Order Value</option>
                        </select>
                    </div>
                    <div class="mt-8 col-md-3">
                        <button type="submit" id="user_sub_btn" class="btn btn-primary ">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (isset($_GET['role']) && isset($_GET['performing']) && isset($_GET['order_by']))
        <div class="row mt-10">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <h1 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Users Report
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
                            @if ($role == 'distributor' || $role == 'sub_stockist')
                                <div class="col-md-3 mb-10">
                                    <label for="sales_rep" class="form-label">
                                        Sales Rep
                                    </label>
                                    <select id="sales_rep" class="form-select" name="sales_rep">
                                        <option value="">
                                            All
                                        </option>
                                        @foreach ($sales_rep as $key => $sales_rep)
                                            <option value="{{ $key }}">
                                                {{ $sales_rep }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                           @if (auth()->user()->role == 'area_manager')
                               <input type="hidden" id="area_manager" name="area_manager" value="{{ auth()->user()->id }}">
                           @else
                            <div class="col-md-3 mb-10">
                                    <label for="area_manager" class="form-label">
                                    Area Managers
                                    </label>
                                    <select id="area_manager" class="form-select" name="area_manager">
                                        <option value="">
                                        All
                                        </option>
                                        @foreach ($area_managers as $key => $area_manager)
                                        <option value="{{ $key }}">
                                        {{ $area_manager }}
                                        </option>
                                        @endforeach
                                    </select>
                            </div>
                           @endif
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
                                <table class="table table-rounded table-striped border gy-7 gs-7" id="users_list_table">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Name</th>
                                            <th>Reference ID</th>
                                            @if ($role != 'sales_rep')
                                                <th>Company Name</th>
                                            @endif

                                            <th>Area Manager</th>
                                            @if ($role == 'distributor' || $role == 'sub_stockist')
                                                <th>Sales Rep</th>
                                            @endif
                                            @if ($role != 'sales_rep')
                                                <th>Place</th>
                                                <th>Target volume</th>
                                                <th>Target acheived</th>
                                                <th>% of Acheivement</th>
                                                <th>Total Order Value</th>
                                            @endif

                                            <th>Points</th>
                                                <th>Rewards</th>


                                            {{-- @if ($_GET['order_by'] == 'points')
                                                <th>Points</th>
                                            @elseif($_GET['order_by'] == 'percentage_of_acheivement')
                                                <th>% of Acheivement</th>
                                            @elseif($_GET['order_by'] == 'total_order_value')
                                                <th>Total Order Value</th>
                                            @elseif($_GET['order_by'] == 'rewards')
                                                <th>Rewards</th> --}}
                                            {{-- {{-- @endif --}}
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




@if (isset($_GET['role']) && isset($_GET['performing']) && isset($_GET['order_by']))
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
                    // 'Today': [moment(), moment()],
                    // 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    // 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    // 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    // 'This Month': [moment().startOf('month'), moment().endOf('month')],
                    // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    //     .endOf('month')
                    // ],
                    'This Year': [startDate, endtDate],
                    'Last Year': [new Date(startYear - 1, 3, 1), new Date(endYear - 1, 2, 31)],
                };

                $('#daterange').daterangepicker({
                    showCustomRangeLabel: false,
                    startDate: new Date(startYear - 1, 3, 1),
                    endDate: new Date(endYear - 1, 2, 31),
                    ranges: __dateRanges
                });


                var users_list_table = $('#users_list_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/admin/user/reports',
                        data: function(d) {
                            d.state = $('#state').val();
                            d.sales_rep = $('#sales_rep').val();
                            d.area_manager = $('#area_manager').val();
                            d.role = '{{ $role }}';
                            d.performing = '{{ $performing }}';
                            d.order_by = '{{ $order_by }}';
                            d.start_date = $('#daterange')
                                .data('daterangepicker').startDate.format('YYYY-MM-DD');
                            d.end_date = $('#daterange')
                                .data('daterangepicker').endDate.format('YYYY-MM-DD');
                        }
                    },
                    @if ($role == 'distributor' || $role == 'sub_stockist')
                        @if ($order_by == 'points')
                            @if ($performing == 'best')
                                order: [
                                    [10, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [10, "asc"]
                                    ],
                            @endif
                        @endif
                        @if ($order_by == 'rewards')
                                @if ($performing == 'best')
                                    order: [
                                        [11, "desc"]
                                    ],
                                @elseif ($performing == 'worst')
                                    order: [
                                            [11, "asc"]
                                        ],
                                @endif
                            @endif

                        @if ($order_by == 'target_acheived')
                            @if ($performing == 'best')
                                order: [
                                    [7, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [7, "asc"]
                                    ],
                            @endif
                        @endif

                        @if ($order_by == 'percentage_of_acheivement')
                            @if ($performing == 'best')
                                order: [
                                    [8, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [8, "asc"]
                                    ],
                            @endif
                        @endif

                        @if ($order_by == 'total_order_value')
                            @if ($performing == 'best')
                                order: [
                                    [9, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [9, "asc"]
                                    ],
                            @endif
                        @endif
                    @elseif ($role == 'wholesaler')
                        @if ($order_by == 'points')
                            @if ($performing == 'best')
                                order: [
                                    [9, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [9, "asc"]
                                    ],
                            @endif
                        @endif
                        @if ($order_by == 'rewards')
                                @if ($performing == 'best')
                                    order: [
                                        [10, "desc"]
                                    ],
                                @elseif ($performing == 'worst')
                                    order: [
                                            [10, "asc"]
                                        ],
                                @endif
                            @endif

                        @if ($order_by == 'target_acheived')
                            @if ($performing == 'best')
                                order: [
                                    [6, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [6, "asc"]
                                    ],
                            @endif
                        @endif

                        @if ($order_by == 'percentage_of_acheivement')
                            @if ($performing == 'best')
                                order: [
                                    [7, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [7, "asc"]
                                    ],
                            @endif
                        @endif

                        @if ($order_by == 'total_order_value')
                            @if ($performing == 'best')
                                order: [
                                    [8, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [8, "asc"]
                                    ],
                            @endif
                        @endif
                    @else
                        @if ($order_by == 'points')
                            @if ($performing == 'best')
                                order: [
                                    [3, "desc"]
                                ],
                            @elseif ($performing == 'worst')
                                order: [
                                        [3, "asc"]
                                    ],
                            @endif
                        @endif
                        @if ($order_by == 'rewards')
                                @if ($performing == 'best')
                                    order: [
                                        [4, "desc"]
                                    ],
                                @elseif ($performing == 'worst')
                                    order: [
                                            [4, "asc"]
                                        ],
                                @endif
                            @endif
                    @endif


                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'reference_id',
                            name: 'reference_id'
                        },
                        @if ($role != 'sales_rep')
                            {
                                data: 'company_name',
                                name: 'company_name'
                            },
                        @endif {
                            data: 'areamanager_name',
                            name: 'areamanager_name',
                            searchable: false,
                        },
                        @if ($role == 'distributor' || $role == 'sub_stockist')
                            {
                                data: 'sales_name',
                                name: 'sales_name',
                                searchable: false,
                            },
                        @endif
                        @if ($role != 'sales_rep')
                            {
                                data: 'address_city',
                                name: 'address_city'
                            }, {
                                data: 'target_volume',
                                name: 'target_volume',
                                searchable: false,
                            }, {
                                data: 'total_weight',
                                name: 'total_weight',
                                searchable: false,
                            }, {
                                data: 'percentage_of_acheivement',
                                name: 'percentage_of_acheivement',
                                searchable: false,
                            }, {
                                data: 'total_order_value',
                                name: 'total_order_value',
                                searchable: false,
                            },
                        @endif

                        {
                            data: 'total_points',
                            name: 'total_points',
                            searchable: false,
                        },

                            {
                                data: 'rewards',
                                name: 'rewards',
                                searchable: false,
                            },
                    ],
                });


                $(document).on('change', '#state, #sales_rep, #area_manager, #daterange', function() {
                    // console.log($('#state').val())
                    users_list_table.ajax.reload();
                });
                $(document).on('change', '#role', function() {
                    if ($(this).val() == 'sales_rep') {
                        var html = "<option selected value=''>Choose...</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points') selected @endif @endif value='points'>Points</option>";
                    } else if ($(this).val() == 'wholesaler' || $(this).val() == 'sub_stockist') {
                        var html = "<option selected value=''>Choose...</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points') selected @endif @endif value='points'>Points</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'target_acheived') selected @endif @endif value='target_acheived'>Target Acheived</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'percentage_of_acheivement') selected @endif @endif value='percentage_of_acheivement'>% of Acheivement</option>";

                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'total_order_value') selected @endif @endif value='total_order_value'>Total Order Value</option>";
                            html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards') selected @endif @endif value='rewards'>Reward Value</option>";
                    } else {
                        var html = "<option selected value=''>Choose...</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points') selected @endif @endif value='points'>Points</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'target_acheived') selected @endif @endif value='target_acheived'>Target Acheived</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'percentage_of_acheivement') selected @endif @endif value='percentage_of_acheivement'>% of Acheivement</option>";

                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'total_order_value') selected @endif @endif value='total_order_value'>Total Order Value</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards') selected @endif @endif value='rewards'>Reward Value</option>";
                    }
                    $('#order_by').html(html);
                });
            });
        </script>
    @endsection
@else
    @section('javascript')
        <script>
            $(document).ready(function() {
                $(document).on('change', '#role', function() {

                    if ($(this).val() == 'sales_rep') {
                        var html = "<option selected value=''>Choose...</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points') selected @endif @endif value='points'>Points</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards') selected @endif @endif value='rewards'>Reward Value</option>";
                    } else if ($(this).val() == 'wholesaler' || $(this).val() == 'sub_stockist') {
                        var html = "<option selected value=''>Choose...</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points') selected @endif @endif value='points'>Points</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'target_acheived') selected @endif @endif value='target_acheived'>Target Acheived</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'percentage_of_acheivement') selected @endif @endif value='percentage_of_acheivement'>% of Acheivement</option>";

                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'total_order_value') selected @endif @endif value='total_order_value'>Total Order Value</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards') selected @endif @endif value='rewards'>Reward Value</option>";
                    } else {
                        var html = "<option selected value=''>Choose...</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'points') selected @endif @endif value='points'>Points</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'target_acheived') selected @endif @endif value='target_acheived'>Target Acheived</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'percentage_of_acheivement') selected @endif @endif value='percentage_of_acheivement'>% of Acheivement</option>";

                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'total_order_value') selected @endif @endif value='total_order_value'>Total Order Value</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards') selected @endif @endif value='rewards'>Reward Value</option>";
                        html +=
                            "<option @if (isset($_GET['order_by'])) @if ($_GET['order_by'] == 'rewards') selected @endif @endif value='rewards'>Reward Value</option>";
                    }
                    $('#order_by').html(html);
                });
            })
        </script>
    @endsection
@endif

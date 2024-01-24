@extends('layout.app')
@section('content')

    <div class="row g-5 g-xl-12">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-12">
                <div class="card-header border-0 pt-5">
                    <h1 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">Manage User @if ($user_type == 'sales_rep')
                                Sales Representative
                            @else
                                {{ ucwords(str_replace('_', ' ', $user_type))}}
                            @endif
                        </span>
                    </h1>


                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                        title="" data-bs-original-title="Click to add a user">
                        <a href="{{ route('admin.users.create') . '?usertype=' . $user_type }}"
                            class="btn btn-sm btn-light btn-active-primary">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                        rx="1" transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                        fill="black"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->Add User
                        </a>
                    </div>
                </div>

                <div class="card-body py-3">
                    <div class="row">
                        @if ($user_type == 'distributor' || $user_type == 'wholesaler')
                            <div class="col-md-3 mb-10">
                                <label for="state" class="form-label">
                                    State
                                </label>
                                <select id="state" class="form-select" name="state">
                                    <option value="">
                                        All
                                    </option>
                                    @foreach ($states_list as $key => $state)
                                        <option value="{{ $state }}">
                                            {{ $state }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7" id="sales_list_table">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        @if ($user_type == 'distributor' || $user_type == 'wholesaler' || $user_type == 'super_stockist'  || $user_type == 'sub_stockist')
                                            <th>Action</th>
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Place</th>
                                            <th>state</th>
                                            <th>Phone No.1</th>

                                            @if ($user_type == 'distributor' || $user_type == 'wholesaler')
                                                <th>Area Manager</th>
                                            @endif

                                            @if ($user_type == 'distributor')
                                                <th>Sales Rep</th>
                                            @endif
                                            <th>Target Volume</th>
                                            <th>Target Achieved</th>
                                            <th>Achievement %</th>
                                            <th>Points</th>
                                            
                                            <th>Reference id</th>
                                            <th>Discount(%)</th>
                                            <th>Rewards Card Number</th>
                                            <th>GST Number</th>
                                            <th>PAN Number</th>
                                        @else
                                            <th>Action</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone No.1</th>
                                            <th>Phone No.2</th>
                                            <th>Reference id</th>
                                            @if ($user_type == 'sales_rep' || $user_type == 'sales_man')
                                                <th>Place</th>
                                                <th>Points</th>
                                                <th>Shop Visited</th>
                                                <th>Shop Converted</th>
                                            @endif
                                        @endif
                                    </tr>
                                </thead>
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

            @if ($user_type == 'distributor' || $user_type == 'wholesaler' || $user_type == 'super_stockist' || $user_type == 'sub_stockist')
                var sales_list_table = $('#sales_list_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/admin/users',
                        data: function(d) {
                            d.usertype = '{{ $user_type }}';
                            d.state = $('#state').val();
                        }
                    },
                    columnDefs: [{
                            targets: [0],
                            orderable: false,
                            searchable: false,
                            processing: true,
                            serverSide: true,
                        },

                        @if ($user_type == 'distributor')
                            {
                                targets: [8,9,10,11],
                                searchable: false,
                            },
                        @elseif ($user_type == 'sub_stockist' || $user_type == 'super_stockist'){
                            targets: [6, 7, 8,9],
                            searchable: false, 
                        }
                        @else
                            {
                                targets: [7,8,9,10],
                                searchable: false,
                            },
                        @endif

                    ],
                    columns: [{
                            data: 'action',
                            name: 'action'
                        },
                        {
                            data: 'company_name',
                            name: 'company_name'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'address_city',
                            name: 'address_city'
                        },
                        {
                            data: 'address_state',
                            name: 'users.address_state'
                        },
                        {
                            data: 'phone_no1',
                            name: 'phone_no1'
                        },
                        @if ($user_type == 'distributor' || $user_type == 'wholesaler')
                            {
                                data: 'area_manager',
                                name: 'areamanager.name'
                            },
                        @endif

                        @if ($user_type == 'distributor')
                            {
                                data: 'sales_name',
                                name: 'sales.name'
                            },
                        @endif

                        {
                            data: 'target_tonnage',
                            name: 'target_tonnage',
                        },
                        {
                            data: 'volume',
                            name: 'volume',
                        },
                        {
                            data:'achievement',
                            name:'achievement',
                        },
                        {
                            data: 'points',
                            name: 'total_points',
                        },
                        
                       
                        {
                            data: 'reference_id',
                            name: 'reference_id'
                        },
                        {
                            data: 'distributor_discount',
                            name: 'distributor_discount'
                        },
                        {
                            data: 'rewards_card_number',
                            name: 'rewards_card_number'
                        },
                        {
                            data: 'gst_number',
                            name: 'gst_number'
                        },
                        {
                            data: 'pan_number',
                            name: 'pan_number'
                        }
                    ],
                });
            @else
                var sales_list_table = $('#sales_list_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/admin/users',
                        data: function(d) {
                            d.usertype = '{{ $user_type }}';
                        }
                    },
                    columnDefs: [{
                            targets: [0],
                            orderable: false,
                            searchable: false,
                            processing: true,
                            serverSide: true,
                        },
                        @if ($user_type == 'sales_rep' || $user_type == 'sales_man')
                            {
                                targets: [7, 8, 9],
                                searchable: false,

                            },
                        @endif
                    ],
                    columns: [{
                            data: 'action',
                            name: 'action'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone_no1',
                            name: 'phone_no1'
                        },
                        {
                            data: 'phone_no2',
                            name: 'phone_no2'
                        },
                        {
                            data: 'reference_id',
                            name: 'reference_id'
                        },
                        @if ($user_type == 'sales_rep' || $user_type == 'sales_man')
                            {
                                data: 'address_city',
                                name: 'address_city'
                            }, {
                                data: 'points',
                                name: 'total_points',
                            }, {
                                data: 'shop_visited',
                                name: 'shop_visited'
                            }, {
                                data: 'shop_converted',
                                name: 'shop_converted'
                            }
                        @endif

                    ],
                });
            @endif

            $(document).on('change', '#state', function() {
                console.log($('#state').val())
                sales_list_table.ajax.reload();
            });

            //delete user
            $(document).on('click', '.delete_user', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this user!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "DELETE",
                            url: $(this).data('href'),
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.msg);
                                    sales_list_table.ajax.reload();
                                } else {
                                    toastr.error(response.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

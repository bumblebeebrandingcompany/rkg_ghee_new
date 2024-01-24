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
                        <span class="card-label fw-bolder fs-3 mb-1">Shops Converted</span>
                    </h1>
                    {{-- @if (Auth::user()->role == 'sales_rep' || Auth::user()->role == 'sales_man')
                        <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                            title="" data-bs-original-title="Click to add a shop">
                            @if (Auth::user()->role == 'sales_rep')
                                <a href="{{ route('sales_rep.shops.create') }}"
                                    class="btn btn-sm btn-light btn-active-primary">

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
                                    <!--end::Svg Icon-->Add Shop
                                </a>
                            @else
                                <a href="{{ route('sales_man.shops.create') }}"
                                    class="btn btn-sm btn-light btn-active-primary">

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
                                    <!--end::Svg Icon-->Add Shop
                                </a>
                            @endif
                        </div>
                    @endif --}}
                </div>

                <div class="card-header border-0 pt-5">
                    <div class="col-md-3 mb-10">
                        <label for="status" class="form-label">
                            Status
                        </label>
                        <select id="status" class="form-select" name="status">
                            <option value="">
                                All
                            </option>
                            <option value="pending_for_sub_stockist"> Pending for sub stockist </option>
                            <option value="pending_for_areamanager"> Pending for areamanager </option>
                            <option value="final"> final </option>
                        </select>
                    </div>
                </div>

                <div class="card-body py-3">
                    <div class="row">
                        <div class="table-responsive">

                            <table class="table table-rounded table-striped border gy-7 gs-7" id="shop_list_table">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Action</th>
                                        <th>Shop Name</th>
                                        <th>Reference id</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>PinCode</th>
                                        <th>Assigned distributor/Sub stockist</th>
                                        <th>Sales Rep</th>
                                        <th>GST</th>
                                        <th>Type of client</th>
                                        <th>Existing ghee products</th>
                                        <th>Visited Date</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Decline Reason</h5>
                    <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add_reason" method="POST" action="{{ route('dist.reason') }}">
                        @csrf
                        <div class="form-group">
                            <label for="reason" class="required form-label">Select Reason</label>
                            <select class="form-control" onChange="discshow()" required name="reason" id="reason">
                                <option value="">Select Reason</option>
                                <option value="existing_shop">Existing Shop</option>
                                <option value="bad_paymaster">Bed Paymaster</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group form-label" id="display_disc" style="display:none;">
                            <label for="discribtion">Description</label>
                            <!-- <input type="text" class="form-control" id="discribtion" name="discribtion"
                             placeholder="Enter discribtion"> -->
                            <textarea class="form-control" id="discribtion" name="discribtion" placeholder="Enter discribtion"></textarea>
                        </div>
                        <input type="hidden" id="id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            $('form#add_reason').validate({
                rules: {
                    reason: {
                        required: true,
                    }
                },
                messages: {},
                submitHandler: function(form, e) {
                    if ($('form#add_reason').valid()) {
                        form.submit();
                    }
                }
            });

            var shop_list_table = $('#shop_list_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.shops.index') }}",
                    data: function(d) {
                        d.status = $("#status").val();
                    }
                },

                columnDefs: [{
                    targets: [0],
                    orderable: false,
                    searchable: false
                }, ],
                columns: [{
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'reference_id',
                        name: 'shops.reference_id'
                    },
                    {
                        data: 'contact',
                        name: 'contact'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'pin_code',
                        name: 'pin_code'
                    },
                    {
                        data: 'distributor_name',
                        name: 'distributor.name'
                    },
                    {
                        data: 'sales_rep_name',
                        name: 'sales_rep.name'
                    },
                    {
                        data: 'gst_number',
                        name: 'gst_number'
                    },
                    {
                        data: 'type_of_client',
                        name: 'type_of_client'
                    },
                    {
                        data: 'existing_ghee_products',
                        name: 'existing_ghee_products'
                    },
                    // { data: 'visited_at', name: 'visited_at' },
                    {
                        data: 'visited_at',
                        type: 'num',
                        render: {
                            _: 'display',
                            sort: 'visited_at'
                        }
                    }
                ],

                order: [
                    [11, 'desc']
                ],


            });
            $('#status').on('change', function() {
            console.log($('#status').val())
            shop_list_table.ajax.reload();
        });
        });


      

        function putid(id) {
            // console.log(id);
            document.getElementById('id').value = id;
        }

      


        function discshow() {
            // console.log(document.getElementById('reason').value);
            if (document.getElementById('reason').value == 'other') {
                document.getElementById('display_disc').style.display = "block";
            } else {
                document.getElementById('display_disc').style.display = "none";
            }
        }
    </script>
@endsection

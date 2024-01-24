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
                    <span class="card-label fw-bolder fs-3 mb-1">Shop Visits</span>
                </h1>
                 @if(Auth::user()->role == 'sales_rep' || Auth::user()->role == 'sales_man')   
                <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="" data-bs-original-title="Click to add a shop">
                    <a href="{{route(prefix_route('shops.create'))}}" class="btn btn-sm btn-light btn-active-primary">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black"></rect>
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->Add Shop</a>
                </div>
            @endif
            </div>

            <div class="card-body py-3">
                <div class="row">
                    <div class="table-responsive">

                        <table class="table table-rounded table-striped border gy-7 gs-7" id="shop_list_table">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th>Action</th>
                                    <th>Assign to</th>
                                    <th>Date</th>
                                    <th>Shop Name</th>
                                    <th>Contact</th>
                                    <th>Sales Rep/Man.</th>
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
    $(document).ready(function(){

    var shop_list_table = $('#shop_list_table').DataTable({
                    processing: true,
                    serverSide: true,
                   @if (Auth::user()->role == 'sales_rep')
                   ajax: {
                        url: '/sales_rep/shop-visits',
                        data: function(d) {
                            // d.usertype = '';
                        }
                    },
                   @else
                   ajax: {
                        url: '/sales_man/shop-visits',
                        data: function(d) {
                            // d.usertype = '';
                        }
                    },
                   @endif
                    columnDefs: [
                        { targets: [0], orderable: false, searchable: false },
                    ],
                    order: [[ 1, "desc" ]],
                    columns: [
                        { data: 'action', name: 'action' },
                        { data: 'company_name', name: 'company_name', searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'shops_name', name: 'shops.name' },
                        { data: 'shop_contact', name: 'shops.contact' },
                        { data: 'sales_rep_name', name: 'sales_rep.name' }
                    ],
                });
    });
</script>
@endsection
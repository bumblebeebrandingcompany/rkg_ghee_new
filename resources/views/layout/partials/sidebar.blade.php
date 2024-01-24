<div id="kt_aside" class="aside pb-5 pt-5 pt-lg-0" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'80px', '300px': '100px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Brand-->
    <div class="aside-logo py-8" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="#" class="d-flex align-items-center">
            <img alt="Logo" src="{{ asset('assets/logo.png') }}" class="h-100px logo" />
        </a>
        <!--end::Logo-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid" id="kt_aside_menu">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y my-2 my-lg-5 pe-lg-n1" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
            data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="5px">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-bold"
                id="#kt_aside_menu" data-kt-menu="true">

                @if (Auth::user()->can('show-admin-sidebar'))

                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('admin.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <!-- <div class="menu-item">
       <a class="menu-link active" href="{{ route('admin.home') }}">
        <span class="menu-bullet">
         <span class="bullet bullet-dot"></span>
        </span>
        <span class="menu-title">Dashboard</span>
       </a>
      </div> -->
                        </div>
                    </div>

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Orders</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('admin.orders.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Sub-Stockist Orders
                            </span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active"
                                    href="{{ route('admin.orders.index') . '?ordertype=super_stockist' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>
                        </div>
                    </div>


                    @can('admin_products')
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                            class="menu-item here show py-2">
                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-boxes fs-2"></i>
                                </span>
                                <span class="menu-title">Products</span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                                <div class="menu-item">
                                    <a class="menu-link active" href="{{ route('admin.products') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Products</span>
                                    </a>
                                </div>
                                {{-- <div class="menu-item">
                                    <a class="menu-link active" href="{{ route('admin.price_edit') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Edit Price</span>
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                    @endcan


                    @if (Auth::user()->role == 'area_manager')
                        <div class="menu-item here show py-2 menu_href" data-href="{{ route('admin.shops.index') }}">
                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-shop-window fs-2"></i>
                                </span>
                                <span class="menu-title">Shops</span>
                            </span>
                        </div>
                    @endif

                    @can('admin_users')
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                            class="menu-item here show py-2">
                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-people-fill fs-2"></i>
                                </span>
                                <span class="menu-title">Users</span>
                            </span>

                            <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">

                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('admin.users.index') . '?usertype=sales_rep' }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sales Representative</span>
                                    </a>
                                </div>



                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('admin.users.index') . '?usertype=distributor' }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Distributor</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('admin.users.index') . '?usertype=wholesaler' }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Wholesaler</span>
                                    </a>
                                </div>



                                @can('rkg_management')
                                    <div class="menu-item">
                                        <a class="menu-link active"
                                            href="{{ route('admin.users.index') . '?usertype=admins' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">RKG Management</span>
                                        </a>
                                    </div>
                                    <div class="menu-item">
                                        <a class="menu-link active" href="{{ route('admin.target.index') }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Edit Target</span>
                                        </a>
                                    </div>

                                    <div class="menu-item">
                                        <a class="menu-link active"
                                            href="{{ route('admin.users.index') . '?usertype=super_stockist' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Super stockist</span>
                                        </a>
                                    </div>
                                @endcan
                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('admin.users.index') . '?usertype=sub_stockist' }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sub stockist</span>
                                    </a>
                                </div>
                                {{-- @can('rkg_management')
                                    <div class="menu-item">
                                        <a class="menu-link active"
                                            href="{{ route('admin.users.index') . '?usertype=sales_man' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">Sales Man</span>
                                        </a>
                                    </div>
                                @endcan --}}
                            </div>
                        </div>
                        @if (Auth::user()->role == 'super_admin' || Auth::user()->role == 'area_manager')
                            
                        @if (Auth::user()->role == 'super_admin')
                                <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                                class="menu-item here show py-2">
                                    <span class="menu-link menu-center">
                                        <span class="menu-icon me-0">
                                            <i class="bi bi-tools fs-2"></i>
                                        </span>
                                        <span class="menu-title">Admin tools </span>
                                    </span>

                                    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                                        <div class="menu-item">
                                            <a class="menu-link active"
                                                href="{{ route('admin.orders.index') . '?ordertype=edit_date' }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Edit Order Date</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link active"
                                                href="{{ route('admin.states.index')}}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Add State</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        @endif
                            
                            <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                            class="menu-item here show py-2">
                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-record-circle fs-2"></i>
                                </span>
                                <span class="menu-title">Reports </span>
                            </span>

                            <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('admin.report') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Users Report</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('admin.product_report') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Products Report</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endcan
                @elseif(Auth::user()->role == 'distributor')
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('dist.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <!-- <div class="menu-item">
        <a class="menu-link active" href="{{ route('dist.home') }}">
         <span class="menu-bullet">
          <span class="bullet bullet-dot"></span>
         </span>
         <span class="menu-title">Dashboard</span>
        </a>
       </div> -->
                        </div>
                    </div>
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('dist.shop') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-shop-window fs-2"></i>
                            </span>
                            <span class="menu-title">Shop Converted</span>
                        </span>
                    </div>

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Orders</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('dist.orders.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('dist.orders.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">New Order</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->is_super_stockist)
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                            class="menu-item here show py-2">
                            <span class="menu-link menu-center">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-basket fs-2"></i>
                                </span>
                                <span class="menu-title">Sub-Stockist Orders
                                </span>
                            </span>

                            <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                                <div class="menu-item">
                                    <a class="menu-link active"
                                        href="{{ route('dist.orders.index') . '?ordertype=super_stockist' }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">All Orders</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif (Auth::user()->role == 'sub_stockist')
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('sub_stockist.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <!-- <div class="menu-item">
    <a class="menu-link active" href="{{ route('dist.home') }}">
     <span class="menu-bullet">
      <span class="bullet bullet-dot"></span>
     </span>
     <span class="menu-title">Dashboard</span>
    </a>
   </div> -->
                        </div>
                    </div>


                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Orders</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('sub_stockist.orders.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('sub_stockist.orders.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">New Order</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('sub_stockist.shop') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-shop-window fs-2"></i>
                            </span>
                            <span class="menu-title">Shops</span>
                        </span>
                    </div>
                @elseif (Auth::user()->role == 'super_stockist')
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('super_stockist.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <!-- <div class="menu-item">
    <a class="menu-link active" href="{{ route('dist.home') }}">
     <span class="menu-bullet">
      <span class="bullet bullet-dot"></span>
     </span>
     <span class="menu-title">Dashboard</span>
    </a>
   </div> -->
                        </div>
                    </div>
                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Orders</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('super_stockist.orders.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('super_stockist.orders.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">New Order</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Sub-Stockist Orders
                            </span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active"
                                    href="{{ route('super_stockist.orders.index') . '?ordertype=super_stockist' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif(Auth::user()->role == 'wholesaler')
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('wholesaler.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <!-- <div class="menu-item">
        <a class="menu-link active" href="{{ route('dist.home') }}">
         <span class="menu-bullet">
          <span class="bullet bullet-dot"></span>
         </span>
         <span class="menu-title">Dashboard</span>
        </a>
       </div> -->
                        </div>
                    </div>
                    <!-- <div class="menu-item here show py-2 menu_href" data-href="{{ route('dist.shop') }}" >
     <span class="menu-link menu-center">
      <span class="menu-icon me-0">
       <i class="bi bi-shop-window fs-2"></i>
      </span>
      <span class="menu-title">Shops</span>
     </span>
    </div> -->

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Orders</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('wholesaler.orders.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">All Orders</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('wholesaler.orders.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">New Order</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif(Auth::user()->role == 'sales_rep')
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('sales_rep.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                        </div>
                    </div>

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-people fs-2"></i>
                            </span>
                            <span class="menu-title">Contacts</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active"
                                    href="{{ route('sales_rep.contacts') . '?type=distributor' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Distributors</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link active"
                                    href="{{ route('sales_rep.contacts') . '?type=sub_stockist' }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Sub Stockist</span>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Sales</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('sales_rep.shops.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Shops Converted</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('sales_rep.shop-visits.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Shop Visits</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif (Auth::user()->role == 'sales_man')
                    <div class="menu-item here show py-2 menu_href" data-href="{{ route('sales_rep.home') }}">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-grid-fill fs-2"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </span>
                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                        </div>
                    </div>

                    {{-- <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                    class="menu-item here show py-2">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="bi bi-people fs-2"></i>
                        </span>
                        <span class="menu-title">Contacts</span>
                    </span>

                    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                        <div class="menu-item">
                            <a class="menu-link active" href="{{ route('sales_rep.distributors') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Distributors</span>
                            </a>
                        </div>
                    </div>
                </div> --}}

                    <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                        class="menu-item here show py-2">
                        <span class="menu-link menu-center">
                            <span class="menu-icon me-0">
                                <i class="bi bi-basket fs-2"></i>
                            </span>
                            <span class="menu-title">Sales</span>
                        </span>

                        <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('sales_man.shops.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Add Shops
                                    </span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a class="menu-link active" href="{{ route('sales_man.shop-visits.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Shop Visits</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- common route for all role -->
                <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start"
                    class="menu-item here show py-2">
                    <span class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="bi bi-download fs-2"></i>
                        </span>
                        <span class="menu-title">Download</span>
                    </span>
                    <div class="menu-sub menu-sub-dropdown w-225px w-lg-275px px-1 py-4">
                        <div class="menu-item">
                            <a class="menu-link active" href="{{ route('medias.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Download</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /common route for all role -->

            </div>
        </div>
    </div>
</div>


    <!--begin::Chart widget 4-->
    <div class="card card-xl-stretch mb-xl-8">
        <!--begin::Header-->
        <div class="card-header align-items-center border-0 mt-4">
            <!--begin::Title-->
            <h3 class="card-title align-items-start flex-column">
                <span class="fw-bolder mb-2 text-dark">
                    RKG Announcement/Notifications
                </span>

                <!-- <span class="text-muted fw-bold fs-7">890,344 Sales</span> -->
            </h3>

            <!--end::Title-->
            <!--begin::Toolbar-->
            <div class="card-toolbar">
                <!-- <button class="btn btn-sm btn-danger">
                    Clear All
                </button> -->
                <!--begin::Menu-->
                <button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen023.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="4" fill="currentColor"></rect>
                            <rect x="11" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor"></rect>
                            <rect x="15" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor"></rect>
                            <rect x="7" y="11" width="2.6" height="2.6" rx="1.3" fill="currentColor"></rect>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </button>
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Header-->
        <!--begin::Card body-->
        <div class="card-body pt-5">
            <div class="timeline-label">

                @forelse($notifications as $notification)

                    @if($notification->type == 'App\Notifications\OrderStatusChanged')
                        @php
                            $order = App\Models\Order::find($notification->data['order_id'])
                        @endphp

                        @if(empty($order))
                            @continue
                        @endif

                    @elseif($notification->type == 'App\Notifications\PointsAdded')
                        @php
                            $point = App\Models\Point::find($notification->data['point_id'])
                        @endphp

                        @if(empty($point))
                            @continue
                        @endif
                    @endif

                    <div class="timeline-item">
                        <!--begin::Label-->
                        <div class="timeline-label fw-bolder text-gray-800 fs-6">{{$notification->created_at->diffForHumans('', '', true)}}</div>
                        <!--end::Label-->
                        <!--begin::Badge-->
                        <div class="timeline-badge">
                            <i class="fa fa-genderless text-warning fs-1"></i>
                        </div>
                        <!--end::Badge-->
                        <!--begin::Text-->

                        
                        @if($notification->type == 'App\Notifications\OrderStatusChanged')
                            <div class="fw-mormal timeline-content text-muted ps-3">Order <b>#{{$order->reference_id}}</b> status changed to <b>{{App\Models\Order::order_statuses()[$notification->data['new_status']]['label']}}</b></div>
                        @endif

                        @if($notification->type == 'App\Notifications\PointsAdded')
                            <div class="fw-mormal timeline-content text-muted ps-3">
                                <b>Congratulations!</b> {{-- $point->points --}} points added to your account!
                            </div>
                        @endif
                        
                        <!--end::Text-->
                    </div>
                @empty
                    <p>No notification</p>
                @endforelse

            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Chart widget 4-->
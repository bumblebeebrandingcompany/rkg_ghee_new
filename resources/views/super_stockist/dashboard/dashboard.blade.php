<!-- first row -->
{{-- <div class="row">
    <div class="col-md-8">
        <div class="card card-flush">
            <!--begin::Card body-->
            <div class="card-body text-center" id="rewardpoints_chart">
                
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-flush">
            <!--begin::Card body-->
            <div class="card-body text-center">
                <table class="table table-condensed text-start">
                    <tr>
                        <th><strong>Current Year</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            @php
                              $start_year = explode('-', $current_fy_date['start']);
                              $end_year = explode('-', $current_fy_date['end']);
                            @endphp
                            {{$start_year[0]}} - {{$end_year[0]}}
                        </td>
                    </tr>
                    <tr>
                        <th><strong>Tonnes</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            <!-- convert kg to tonnes: 1000kg=1ton -->
                            @php
                                $achieved_tonnage = ($order->total_weight > 0) ? $order->total_weight/1000 : 0;
                            @endphp
                            {{@num_format($achieved_tonnage)}} Tonnes
                        </td>
                    </tr>
                    <tr>
                        <th><strong>Purchase Value</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">Rs {{@num_format($order->total_price)}}</td>
                    </tr>

                    <tr>
                        <th><strong>Points Earned</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            {{$order->points_earned ?? 0}} Points
                        </td>
                    </tr>
                    <tr>
                        <!-- TODO:dyanamic -->
                        <th><strong>Value</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">Rs {{@num_format($order->points_earned*5)}}</td>
                    </tr>
                    <tr>
                        <th><strong>Credit Note</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">Rs {{@num_format($rewards)}}</td>
                    </tr>
                </table>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div> --}}

<!-- 2nd row -->
<div class="row mt-5">
    <div class="col-md-8">
        <div class="card card-flush">
            <!--begin::Card body-->
            <div class="card-body text-center" id="tonnage_chart">
                
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-flush">
            <!--begin::Card body-->
            <div class="card-body text-center">
                <table class="table table-condensed text-start">
                    <tr>
                        <th><strong>Target</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            {{number_format($target_tonnage, 2, '.', ',')}}
                        </td>
                    </tr>
                    <tr>
                        <th><strong>Current year Tonnage</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            <!-- convert kg to tonnes: 1000kg=1ton -->
                            @php
                                $current_year_tonnage = ($order->total_weight > 0) ? $order->total_weight/1000 : 0;
                            @endphp
                            {{number_format($current_year_tonnage, 2, '.', ',')}}
                        </td>
                    </tr>
                    <tr>
                        <th><strong>Pending</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            <!-- gen pending tonnage -->
                            @php
                                $pending_tonnage = $target_tonnage - $current_year_tonnage;
                            @endphp
                            {{number_format($pending_tonnage, 2, '.', ',')}}
                        </td>
                    </tr>

                    <tr>
                        <th><strong>Days Left</strong></th>
                        <td>:</td>
                        <td class="text-color-brown">
                            {{\Carbon\Carbon::parse($current_fy_date['end'])->diffInDays(\Carbon\Carbon::today())}} Days
                        </td>
                    </tr>
                </table>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

<div class="row mt-3">

    {{-- <div class="col-xl-6 mb-5 mb-xl-10">
        @include('layout/partials/notification')
    </div> --}}

    <div class="col-xl-6 mb-5 mb-xl-10">
        <!--begin::Chart widget 4-->
        <div class="card card-flush overflow-hidden h-md-100">
            <!--begin::Header-->
            <div class="card-header py-5">
                <!--begin::Title-->
                <h3 class="card-title ms-auto me-auto">
                    <span class="card-label fw-bolder text-dark">
                        Order Status
                    </span>
                </h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar">
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
            <div class="card-body d-flex justify-content-between flex-column pb-1">
                <div class="row text-center">
                    <div class="col-md-12">
                        <strong>
                            <!-- No Active Orders -->
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        // chart_data = '{!!$charts_data["reward_points_data"]!!}';
        // chart_data.replace(/&quot;/g,'"');
        // chart_data = JSON.parse(chart_data);
        // var data = google.visualization.arrayToDataTable(chart_data);
        // //console.log(data);exit;

        // var view = new google.visualization.DataView(data);
        // view.setColumns([0, 1,
        //                { calc: "stringify",
        //                  sourceColumn: 1,
        //                  type: "string",
        //                  role: "annotation" },
        //                2]);

        // var options = {
        //     title: "Reward Points Chart",
        //     width: 600,
        //     height: 400,
        //     bar: {groupWidth: "95%"},
        //     legend: { position: "none" },
        // };
        // var chart = new google.visualization.ColumnChart(document.getElementById("rewardpoints_chart"));
        // chart.draw(view, options);


      //tonnage chart
        chart_data = '{!!$charts_data["weight_data"]!!}';
        chart_data.replace(/&quot;/g,'"');
        chart_data = JSON.parse(chart_data);
        var data = google.visualization.arrayToDataTable(chart_data);
        //console.log(data);exit;

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

        var options = {
            title: "Tonnage Chart",
            width: 600,
            height: 400,
            bar: {groupWidth: "95%"},
            legend: { position: "none" },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("tonnage_chart"));
        chart.draw(view, options);

  }
</script>
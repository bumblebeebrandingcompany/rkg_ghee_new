@extends('layout.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="">
                Welcome {{$user->name}}
            </h1>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header align-items-center border-0">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="fw-bolder mb-2 text-dark">
                            Shop Visited Performance for {{now()->format('F Y')}}
                        </span>
                    </h3>
                </div>

                <!--begin::Card body-->
                <div class="card-body text-center" id="shop_visit_chart"
                    style="width: 100% !important; height: 100% !important">
                    
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-flush">
                <div class="card-header align-items-center border-0">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="fw-bolder mb-2 text-dark">
                            Shop Converted for {{now()->format('F Y')}}
                        </span>
                    </h3>
                </div>

                <!--begin::Card body-->
                <div class="card-body text-center" id="shop_conversion_chart" 
                    style="width: 100% !important; height: 100% !important">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6 d-flex">
            <div class="card shadow-sm flex-fill">
                <div class="card-header">
                    <!--begin::Title-->
                    <h3 class="card-title">
                        <span class="fw-bolder mb-2 text-dark">
                            Recent Conversions
                        </span>
                    </h3>
                </div>

                <!--begin::Card body-->
                <div class="card-body text-center">
                    <table class="table table-condensed text-start">
                        @foreach($recent_conversion as $conversion)
                            <tr>
                                <td>#{{$loop->iteration}}</td>
                                <td>{{$conversion->name}}</td>
                                <td>{{Carbon\Carbon::create($conversion->sale_status_on)->diffForHumans()}}</td>
                            </tr>
                        @endforeach
                    </table>
                    
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header card-header-stretch">
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_7">This Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_8">Previous Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_9">Distributor</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_tab_pane_7" role="tabpanel">
                            <table class="table table-condensed text-start">
                                <tr>
                                    <td>Month</td>
                                    <td>:</td>
                                    <td>{{now()->format('M Y')}}</td>
                                </tr>

                                <tr>
                                    <td>Shops Visited</td>
                                    <td>:</td>
                                    <td>{{$this_month_visit}}</td>
                                </tr>

                                <tr>
                                    <td>Shops Converted</td>
                                    <td>:</td>
                                    <td>{{$this_month_conver}}</td>
                                </tr>

                                <!-- <tr>
                                    <td>Points Earned</td>
                                    <td>:</td>
                                    <td>{{@num_format($this_month_points)}}</td>
                                </tr>

                                <tr>
                                    <td>Value in Rupees</td>
                                    <td>:</td>
                                    <td>Rs. {{@num_format($this_month_points*5)}}</td>
                                </tr> -->

                                {{-- <tr>
                                    <td colspan="3">
                                        <h5>Terms and Conditions</h5>

                                        <small>
                                            <ul>
                                                <li>Minimum of 60 shop visits per month to claim the 150 reward points</li>
                                                <li>Minimum of 2 shop conversions per month to claim reward points</li>
                                                <li>₹ 5 per point</li>
                                                <!-- <li>Point can be redeemed 31st of march every year</li> -->
                                            </ul>
                                        </small>
                                    </td>
                                </tr> --}}

                            </table>

                        </div>

                        <div class="tab-pane fade" id="kt_tab_pane_8" role="tabpanel">
                            <table class="table table-condensed text-start">
                                <tr>
                                    <td>Previous Month</td>
                                    <td>:</td>
                                    <td>{{now()->subMonth()->format('M Y')}}</td>
                                </tr>

                                <tr>
                                    <td>Shops Visited</td>
                                    <td>:</td>
                                    <td>{{$prev_month_visit}}</td>
                                </tr>

                                <tr>
                                    <td>Shops Converted</td>
                                    <td>:</td>
                                    <td>{{$prev_month_conver}}</td>
                                </tr>

                                <!-- <tr>
                                    <td>Points Earned</td>
                                    <td>:</td>
                                    <td>{{@num_format($prev_month_point)}}</td>
                                </tr>

                                <tr>
                                    <td>Value in Rupees</td>
                                    <td>:</td>
                                    <td>Rs. {{@num_format($prev_month_point*5)}}</td>
                                </tr> -->
                            </table>
                        </div>

                        <div class="tab-pane fade" id="kt_tab_pane_9" role="tabpanel">
                            <table class="table table-condensed text-start">
                                <tr>
                                    <td>No of distributors</td>
                                    <td>:</td>
                                    <td>{{$no_of_distributor}}</td>
                                </tr>

                                {{-- <tr>
                                    <td>Points Earned by Distributors</td>
                                    <td>:</td>
                                    <td>{{@num_format($points_by_dist)}}</td>
                                </tr> --}}

                                {{-- <tr>
                                    <td>Points Earned</td>
                                    <td>:</td>
                                    <td>{{@num_format($dist_point_to_salesrep)}}</td>
                                </tr> --}}

                                {{-- <tr>
                                    <td>Value in Rupees</td>
                                    <td>:</td>
                                    <td>Rs. {{@num_format($dist_point_to_salesrep*5)}}</td>
                                </tr> --}}

                                {{-- <tr>
                                    <td colspan="3">
                                        <h5>Terms and Conditions</h5>
                                        
                                        <small>
                                            <ul>
                                                <li>Sales rep will get 50 points for every 1000 points earned by distributor under the sales rep.</li>
                                                <li>5 Rupees per point</li>
                                                <li>Point can be redeemed 31st of march every year</li>
                                            </ul>
                                        </small>
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- <div class="row mt-3">
    <div class="col-xl-10 offset-md-1 mb-5 mb-xl-10 mt-4">
        @include('layout/partials/notification')
    </div>
</div> --}}

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        chart_data = '{!!$sv_formatted!!}';
        chart_data.replace(/&quot;/g,'"');
        chart_data = JSON.parse(chart_data);
        var data = google.visualization.arrayToDataTable(chart_data);

        var options = {
          title: '',
          curveType: 'function',
          legend: { position: 'bottom' },
          vAxis: { title: "Shops" },

        };
        var chart = new google.visualization.ColumnChart(document.getElementById('shop_visit_chart'));
        chart.draw(data, options);


        chart_data = '{!!$sc_formatted!!}';
        chart_data.replace(/&quot;/g,'"');
        chart_data = JSON.parse(chart_data);
        var data = google.visualization.arrayToDataTable(chart_data);

        var options = {
          title: '',
          curveType: 'function',
          legend: { position: 'bottom' },
          vAxis: { title: "Shops" },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('shop_conversion_chart'));
        chart.draw(data, options);

    }
</script>
@endsection
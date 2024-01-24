@extends('layout.app')
@section('content')
    @php
        $total_boxes = 0;
    @endphp

    <div class="row g-5 g-xl-12">
        <div class="col-xl-12">
            <div class="card card-xl-stretch mb-xl-12">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Order: <code>{{ $order->reference_id }}</code></span>

                        <span class="card-label fw-bold fs-3 mt-2">

                            @if ($distributor->is_super_stockist)
                            Super Stockists: <br />
                            @else
                            Company: <br />
                            @endif

                           
                            {{ $distributor->company_name }}<br />
                            {{ $distributor->name }}<br />
                            {{ $distributor->address_line_1 }}<br />
                            {{ $distributor->address_line_2 }}<br />

                            {{ implode(', ', [$distributor->address_city, $distributor->address_state, $distributor->address_zip]) }}<br />
                            Mobile: {{ $distributor->phone_no1 }}<br />
                            Email: {{ $distributor->email }}<br />
                            Id: {{ $distributor->reference_id }}<br />
                            <br /><br />
                        </span>

                        <span class="card-label fw-bold fs-3 mb-1">Date: {{ $order->created_at->format('d-m-Y H:i') }}</span>

                        <span class="card-label fw-bold fs-3 mb-1">Invoice No.: <span
                                class="{{ $status['class'] }}">{{ $order->invoice_no }}</span>

                            <br />

                            <span class="card-label fw-bold fs-3 mb-1">Status: <span
                                    class="{{ $status['class'] }}">{{ $status['label'] }}</span>

                                @if (!empty($order->distributor_notes))
                                    <br />

                                    <span class="card-label fw-bold fs-3 mb-1">Distributor notes:
                                        {{ $order->distributor_notes }}</span>
                                @endif
                    </h3>
                    @if (!empty($order->sub_stockist_id))
                        <div class="card-toolbar">
                            <span class="card-label fw-bold fs-3 mt-2">
                                <strong> Sub Stockist Detail </strong><br />
                                Company: <br />
                                {{ $sub_stockist->company_name }}<br />
                                {{ $sub_stockist->name }}<br />
                                {{ $sub_stockist->address_line_1 }}<br />
                                {{ $sub_stockist->address_line_2 }}<br />
                                {{ implode(', ', [$sub_stockist->address_city, $sub_stockist->address_state, $sub_stockist->address_zip]) }}<br />
                                Mobile: {{ $sub_stockist->phone_no1 }}<br />
                                Email: {{ $sub_stockist->email }}<br />
                                Id: {{ $sub_stockist->reference_id }}<br />
                                <br /><br />
                            </span>

                        </div>
                    @endif

                    @if (!empty($order->invoice_file_name))
                        <div class="card-toolbar">
                            <a href='{{ route('download.invoice', ['id' => $order->id]) }}' class="btn btn-sm btn-light">
                                Download Invoice
                            </a>
                        </div>
                    @endif
                </div>


                <div class="card-body py-3">
                    <div class="row">

                        <div class="col-md-12">
                            <table class="table table-row-dashed table-row-gray-300 gy-7">
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Order Quantity</th>
                                    @if (empty($order->sub_stockist_id))
                                        <th>Unit Price Amount</th>
                                        <th>Amount</th>
                                    @endif
                                @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                                    <th>Points earned</th>
                                @endif
                                    
                                </tr>

                                @foreach ($order->order_lines as $line)
                                    @if (empty($line['product']))
                                        @continue
                                    @endif

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>

                                            @if ($line['product']->product_type == 'cow_ghee')
                                                Cow Ghee
                                            @elseif($line['product']->product_type == 'buffalo_ghee')
                                                Buffalo Ghee
                                            @elseif($line['product']->product_type == 'thelivu_ghee')
                                                Thelivu Ghee
                                            @elseif($line['product']->product_type == 'roasted_cow_ghee')
                                                Roasted Cow Ghee
                                            @endif
                                            @if ($line['product']->pack_type != 'sachet')
                                                <br />Packing: <b>
                                                    {{ $line['product']->pack_size . ' ' . ucfirst($line['product']->pack_size_unit) . ' ' . ucfirst($line['product']->pack_type) }}</b>
                                            @elseif($line['product']->pack_type == 'sachet')
                                                <br> Packing:
                                                <b>
                                                    {{-- {{$line['product']->pcs_per_bundle}} {{ 'Sachets' }} --}}
                                                    @if ($line['product']->barcodes == 'CS0001')
                                                        Rs. 5 per sachet
                                                </b>
                                            @elseif($line['product']->barcodes == 'CS0002')
                                                Rs. 10 per sachet</b>
                                            @elseif($line['product']->barcodes == 'CS0003')
                                                Rs. 10 per sachet</b>
                                            @elseif($line['product']->barcodes == 'CS0004')
                                                Rs. 20 per sachet</b>
                                            @elseif($line['product']->barcodes == 'BS0001')
                                                Rs. 10 per sachet</b>
                                            @elseif($line['product']->barcodes == 'BS0002')
                                                Rs. 20 per sachet</b>
                                            @endif
                                @endif


                                <!--   @if (!empty($line['product']->additional_info))
    <b>{{ $line['product']->additional_info }}</b><br/>
    @endif

                                                                    <b>SKU:</b> {{ $line['product']->barcodes }}<br/>
                                                                    
                                                                    @if ($line['product']->pack_size != 0)
    {{ $line['product']->pack_size . ' ' . $line['product']->pack_size_unit }}
    @endif
                                                                    
                                                                    <br/><b>Packing:</b> {{ $line['product']->pack_type }}
                                                                    <br/><b>Weight:</b> {{ $line['product']->weight_per_bundle }} Kg
                                 -->
                                </td>

                                <td>
                                    {{ $line['product']->barcodes }}
                                </td>

                                <td>
                                    {{ $line->quantity }} Box(s)

                                    @php
                                        $total_boxes += $line->quantity;
                                    @endphp
                                </td>

                                @if (empty($order->sub_stockist_id))
                                    <td>Rs. {{ @num_format($line->line_price / $line->quantity) }}</td>
                                    <td>Rs. {{ @num_format($line->line_price) }}</td>
                                @endif

                            @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                                    @if (empty($order->sub_stockist_id))
                                        <td>{{ @num_format($line->points_earned) }}</td>
                                    @else
                                        <td>{{ (80 / 100) * $line->points_earned }}</td>
                                    @endif
                            @endif




                                </tr>
                                @endforeach

                                @if (empty($order->sub_stockist_id))
                                    <tr class="fw-bolder fs-4 text-gray-800">
                                        <td colspan="5" class="pull-end">
                                            @if ($order->discount_percent > 0)
                                                Subtotal: Rs. {{ @num_format($order->subtotal_amount) }}
                                                <br />
                                                Discount: {{ $order->discount_percent }} % (Rs.
                                                {{ ($order->subtotal_amount * $order->discount_percent) / 100 }})
                                                <br />
                                            @endif
                                            GST: {{ $order->gst_percent }} % (Rs. {{ @num_format($order->gst_price) }})
                                            <br />
                                            @if (!empty($order->used_credit_notes_amount))
                                                Use credit note : Rs. {{ $order->used_credit_notes_amount }}
                                                <br />
                                    Total Amount: Rs. {{ @num_format($order->total_price) }}
                                        @endif
                                        </td>
                                    </tr>
                                @endif




                                <tr class="fw-bolder fs-4 text-gray-800">
                                    <td colspan="5" class="pull-end">
                                        Total Box(s): {{ $total_boxes }}
                                        <br />
                                        Total Tonnage: {{ @num_format($order->total_weight / 1000) }}
                                        <br />

                                    @if (Auth::user()->role != 'distributor' && Auth::user()->role != 'super_stockist' && Auth::user()->role != 'sub_stockist' && Auth::user()->role != 'wholesaler')
                                        @if (empty($order->sub_stockist_id))
                                            Total Points Earned : {{ $order->total_points }}
                                        @else
                                            Total Points Earned  : {{ (80 / 100) * $order->total_points }}
                                        @endif
                                    @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <!-- <div class="col-md-12 mt-2 ">
                                                    <a href="{{ route('dist.orders.edit', $order->id) }}" class="btn btn-light-primary me-2 mb-2 float-end mx-2" name="order_status" value="draft">Modify Order</a>
                                                </div> -->
                </div>

            </div>
        </div>
    </div>
@endsection

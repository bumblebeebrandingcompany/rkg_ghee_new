<!-- <table class="table gs-1 gy-1 text-muted">
    @if ($product->is_buffalo_ghee)
<tr>
        <td colspan="2">Buffalo Ghee</td>
    </tr>
@endif
    @if ($product->additional_info)
<tr>
        <td colspan="2">{{ $product->additional_info }}</td>
    </tr>
@endif
    <tr>
        <td class="col-md-8">Product:</td>
        <td class="col-md-4"> {{ $product_name ?? '' }}</td>
    </tr>
    <tr>
        <td class="col-md-8">SKU:</td>
        <td class="col-md-4">{{ $product->barcodes }}</td>
    </tr>
    <tr>
        <td class="col-md-8">Packing:</td>
        <td class="col-md-4"> {{ ucFirst($product->pack_type) }}</td>
    </tr>
    <tr>
        <td>Weight in Kg/Gm per piece:</td>
        <td>{{ $product->weight_per_bundle / $product->pcs_per_bundle }}</td>
    </tr>
    <tr>
        <td>Weight per Carton/Box: </td>
        <td>{{ $product->weight_per_bundle }}</td>
    </tr>
    <tr>
        <td>Points per carton/Box: </td>
        <td>{{ $product->points_per_bundle }}</td>
    </tr>
    <tr>
        <td>Basic price per piece: </td>
        <td>Rs. {{ $product->price_per_bundle / $product->pcs_per_bundle }}</td>
    </tr>
    <tr>
        <td>Price per carton: </td>
        <td>Rs. {{ $product->price_per_bundle }}</td>
    </tr>
</table> -->
<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header text-center" id="headingOne">
            <button class=" btn btn-default btn-sm m-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#{{ $product->barcodes }}" aria-expanded="true"
                aria-controls="{{ $product->barcodes }}">
                <i class="fa fa-info" aria-hidden="true"></i>
            </button>
        </h2>
        <div id="{{ $product->barcodes }}" class="accordion-collapse collapse" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <table class="table gs-1 gy-1 text-muted">
                    @if ($product->is_buffalo_ghee)
                        <tr>
                            <td colspan="2">Buffalo Ghee</td>
                        </tr>
                    @endif
                    @if ($product->additional_info)
                        <tr>
                            <td colspan="2">{{ $product->additional_info }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="col-md-8">Product:</td>
                        <td class="col-md-4"> {{ $product_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="col-md-8">SKU:</td>
                        <td class="col-md-4">{{ $product->barcodes }}</td>
                    </tr>
                    <tr>
                        <td class="col-md-8">Packing:</td>
                        <td class="col-md-4"> {{ ucFirst($product->pack_type) }}</td>
                    </tr>
                    <tr>
                        <td>Weight in Kg/Gm per piece:</td>
                        <td>{{ $product->weight_per_bundle / $product->pcs_per_bundle }}</td>
                    </tr>
                    <tr>
                        <td>Weight per Carton/Box: </td>
                        <td>{{ $product->weight_per_bundle }}</td>
                    </tr>
                    @if (Auth::user()->role == 'distributor' || Auth::user()->role == 'super_stockist')
                        {{-- <tr>
                            <td>Points per carton/Box:</td>
                            <td>{{ $product->distributer_point }}</td>
                        </tr> --}}
                    @elseif(Auth::user()->role == 'wholesaler')
                        {{-- <tr>
                            <td>Points per carton/Box:</td>
                            <td>{{ $product->wholesaler_point }}</td>
                        </tr> --}}
                    @else
                        @if ($display)
                            @if ($user_type == 'distributor' || $user_type == 'sub_stockist' || $user_type == 'super_stockist')
                                <tr>
                                    <td>Points per carton/Box: (Distributor)</td>
                                    <td>{{ $product->distributer_point }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td>Points per carton/Box: (Wholesaler)</td>
                                    <td>{{ $product->wholesaler_point }}</td>
                                </tr>
                            @endif
                        @endif
                    @endif
                    @if (Auth::user()->role != 'sub_stockist' && !isset($_GET['ordertype']))
                    <tr>
                        <td>Basic price per piece: </td>
                        <td>Rs. {{ @num_format($product->latest_price / $product->pcs_per_bundle) }}</td>
                    </tr>
                    <tr>
                        <td>Price per carton: </td>
                        <td>Rs. {{ $product->latest_price }}</td>
                    </tr>  
                    @endif
                   
                </table>
            </div>
        </div>
    </div>
</div>

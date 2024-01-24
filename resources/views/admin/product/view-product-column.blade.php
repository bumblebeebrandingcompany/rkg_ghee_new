<div class="card card-xl-stretch mb-xl-12 shadow-sm">
    <div class="card-header border-0 pt-5 justify-content-center">
        <h3 class="card-title align-items-start flex-column">
            <span
                class="card-label fw-bolder fs-3 mb-1 text-color-yellow color-brown p-5 rounded-pill">{{ $heading }}</span>
        </h3>
    </div>

    <div class="card-body py-3">

        <table class="table">
            <tr>
                <td><img class="img-fluid" src="{{ $image }}"
                        style="height: 250px !important; margin-left: auto; margin-right: auto; display: block;"></td>
            </tr>
            @foreach ($products as $product)
                @if ($product->pack_type == $pack_type && $product->product_type == $product_type)
                    <tr>
                        <td>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text custom-group-text">
                                        @if ($product->pack_type != 'sachet')
                                            {{ $product->pack_size }}

                                            @if ($product->pack_size_unit == 'ltr')
                                                L
                                            @else
                                                {{ ucfirst($product->pack_size_unit) }}.
                                            @endif
                                        @elseif($product->pack_type == 'sachet')
                                            {{-- {{$product->additional_info}} --}}
                                            @if ($product->barcodes == 'CS0001')
                                                Rs. 5 per sachet
                                            @endif
                                            @if ($product->barcodes == 'CS0002')
                                                Rs. 10 per sachet
                                            @endif
                                            @if ($product->barcodes == 'CS0003')
                                                Rs. 10 per sachet
                                            @endif
                                            @if ($product->barcodes == 'CS0004')
                                                Rs. 20 per sachet
                                            @endif
                                            @if ($product->barcodes == 'BS0001')
                                                Rs. 10 per sachet
                                            @endif
                                            @if ($product->barcodes == 'BS0002')
                                                Rs. 20 per sachet
                                            @endif
                                        @endif
                                    </span>



                                    <span class="input-group-text custom-group-text">
                                        box
                                    </span>
                                </div>
                                @include('orders.product_info', [
                                    'product' => $product,
                                    'product_name' => $heading,
                                    'display' => false,
                                ])
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</div>

@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="">
                Editing Order <code>{{ $order->reference_id }}</code>
            </h1>
        </div>
    </div>

    @if (isset($_GET['ordertype']))
    <form method="POST" action="{{ route(prefix_route('orders.update'), $order->id) }}?ordertype=super_stockist" id="editorder">
    @else
    <form method="POST" action="{{ route(prefix_route('orders.update'), $order->id) }}" id="editorder">
    @endif

        @csrf
        @method('PUT')

        <div class="row g-5 g-xl-12">
            @csrf
            <div class="col-md-4">

                @include('orders.partials.edit-product-column', [
                    'heading' => 'COW GHEE',
                    'pack_type' => 'tin',
                    'image' => asset('assets/products-tin.png'),
                    'product_type' => 'cow_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'COW GHEE',
                    'pack_type' => 'jar',
                    'image' => asset('assets/products-jar.png'),
                    'product_type' => 'cow_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'COW GHEE',
                    'pack_type' => 'pouch',
                    'image' => asset('assets/products-pouch.png'),
                    'product_type' => 'cow_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'COW GHEE',
                    'pack_type' => 'sachet',
                    'image' => asset('assets/product-cowghee-sachets.jpeg'),
                    'product_type' => 'cow_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'BUFFALO GHEE',
                    'pack_type' => 'sachet',
                    'image' => asset('assets/product-buffaloghee-sachets.jpeg'),
                    'product_type' => 'buffalo_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'BUFFALO GHEE',
                    'pack_type' => 'tin',
                    'image' => asset('assets/Bufflo-Ghee.png'),
                    'product_type' => 'buffalo_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'BUFFALO GHEE',
                    'pack_type' => 'jar',
                    'image' => asset('assets/buffalo-ghee-jar.png'),
                    'product_type' => 'buffalo_ghee',
                ])
            </div>

            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'THELIVU GHEE',
                    'pack_type' => 'tin',
                    'image' => asset('assets/Liquid-Ghee-01.png'),
                    'product_type' => 'thelivu_ghee',
                ])
            </div>
            <div class="col-md-4">
                @include('orders.partials.edit-product-column', [
                    'heading' => 'ROASTED COW GHEE',
                    'pack_type' => 'jar',
                    'image' => asset('assets/rosted_cow_jhee.png'),
                    'product_type' => 'roasted_cow_ghee',
                ])
            </div>



            <div class="card">
                <div class="card-body">
                    <div class="rounded border d-flex flex-column p-10">
                        @if (Auth::user()->role == 'distributor' && number_format($rewards) > 0)
                            <div class="col-md-4">
                                <label for="rewards" class="form-label">Your rewards Rs .
                                    {{ number_format($rewards) }}</label>
                                <br>
                                <label for="rewards" class="form-label">Use Rewards</label>
                                <input class="form-control form-control-solid" name="rewards"
                                    value="{{ $order->used_credit_notes_amount }}" id="rewards" type="number"
                                    step="any">
                            </div>
                            <br>
                        @endif
                        <label for="" class="form-label">Additional notes for order</label>
                        <textarea class="form-control form-control-solid" rows=5 name="distributor_notes">{{ $order->distributor_notes }}</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-md-12 mt-2 ">
                        <button type="submit" class="btn btn-light-primary me-2 mb-2 float-end mx-2 save_order_draft"
                            name="order_status" value="draft">Save as Draft</button>
                          
                        <button type="submit" class="btn btn-success btn-hover-scale float-end" name="order_status"
                            value="order_placed"><i class="bi bi-send fs-4 me-2"></i> Place Order</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            $.validator.addMethod("lessThan",
                function(value, element, param) {
                    var i = parseFloat(value);
                    if (isNaN(i)) {
                        i = 0;
                    }
                    var j = parseFloat(param);
                    return (i <= j) ? true : false;
                }, "Please check credit rewards"
            );
            $('#editorder').validate({

                rules: {
                    rewards: {
                        lessThan: parseFloat({{ $rewards }})
                    }
                },


            });
        });
    </script>
@endsection

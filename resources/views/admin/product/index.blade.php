@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h1 class="">
            Manage Products
        </h1>
    </div>
</div>

    
<div class="row g-5 g-xl-12">
    <div>
        <h4>Manage Price</h4>
        @foreach ($product_prices as $price)
            <a href="{{ route('admin.price_edit') }}?start_date={{ $price->start_date }}"  class="btn  btn-sm btn-primary m-2">{{ Carbon\Carbon::parse($price->start_date)->toFormattedDateString() }}</a>
        @endforeach
            <a href="{{ route('admin.add_price') }}"  class="btn  btn-sm btn-success m-2">Add new price</a>
    </div>
    
   <div>
    <h4>Manage Points</h4>
     @foreach ($states_list as $key => $state)
     <a href="{{ route('admin.state_by_point', urlencode($state)) }}"  class="btn  btn-sm btn-primary m-2">{{ $state }}</a>
     @endforeach
   </div>

   
    <div class="col-md-4">

        @include('admin.product.view-product-column', ['heading' => 'COW GHEE', 'pack_type' => 'tin', 'image' => asset('assets/products-tin.png'), 'product_type' => 'cow_ghee'])
    </div>

    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'COW GHEE', 'pack_type' => 'jar', 'image' => asset('assets/products-jar.png'), 'product_type' => 'cow_ghee'])
    </div>

    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'COW GHEE', 'pack_type' => 'pouch', 'image' => asset('assets/products-pouch.png'), 'product_type' => 'cow_ghee'])
    </div>
    
    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'COW GHEE', 'pack_type' => 'sachet', 'image' => asset('assets/product-cowghee-sachets.jpeg'), 'product_type' => 'cow_ghee'])
    </div>
    
    {{-- new column for sachet buffalo ghee --}}

    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'BUFFALO GHEE', 'pack_type' => 'sachet', 'image' => asset('assets/product-buffaloghee-sachets.jpeg'), 'product_type' => 'buffalo_ghee'])
    </div>

     {{-- end column for sachet buffalo ghee --}}

    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'BUFFALO GHEE', 'pack_type' => 'tin', 'image' => asset('assets/Bufflo-Ghee.png'), 'product_type' => 'buffalo_ghee'])
    </div>

    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'BUFFALO GHEE', 'pack_type' => 'jar', 'image' => asset('assets/buffalo-ghee-jar.png'), 'product_type' => 'buffalo_ghee'])
    </div>

    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'THELIVU GHEE', 'pack_type' => 'tin', 'image' => asset('assets/Liquid-Ghee-01.png'), 'product_type' => 'thelivu_ghee'])
    </div>
    <div class="col-md-4">
        @include('admin.product.view-product-column', ['heading' => 'ROASTED COW GHEE', 'pack_type' => 'jar', 'image' => asset('assets/rosted_cow_jhee.png'), 'product_type' => 'roasted_cow_ghee'])
    </div>
</div>
@endsection
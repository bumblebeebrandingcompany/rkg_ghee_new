@extends('layout.app')
@section('content')
    <div class="row g-5 g-xl-12">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="col-xl-12">
            <form action="{{ route('admin.price_update')}}" method="post">
                <div class="card card-xl-stretch mb-xl-12">
                    <div class="card-header border-0 pt-5">
                        <h1 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">Price of Date {{ \Carbon\Carbon::parse(request()->input('start_date'))->format('j M Y') }}</span>
                            <small><code>This price will be applicable for orders placed on or after the date</code></small>
                        </h1>
                        {{-- <div class="card-toolbar">
                            <input type="submit" class="btn btn-success" value="Update">
                            @csrf
                        </div> --}}

                    </div>

                    <div class="card-body py-3">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-rounded table-striped border gy-7 gs-7">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>SKU</th>
                                            <th>Price Per Carton</th>
                                        </tr>
                                        @php
                                            $c = 1;
                                        @endphp
                                        @foreach ($products as $value)
                                            <tr>
                                                <td>{{ $c++ }}</td>


                                                @if ($value->pack_type == 'sachet')
                                                    <td>{{ ucfirst(str_replace('_', ' ', $value->product_type)) }}
                                                       
                                                           ( {{ $value->pcs_per_bundle }} Sockets )
                                                   
                                                      
                                                    </td>
                                                @else
                                                    <td>{{ ucfirst(str_replace('_', ' ', $value->product_type)) }}({{ $value->pack_size . ' ' . $value->pack_size_unit . ' ' . $value->pack_type }})
                                                    </td>
                                                @endif
                                                <td>{{ $value->barcodes }}</td>
                                                <td> <input type="number" 
                                                        name="products[{{ $c }}][price]" multiple
                                                        required class="form-control" readonly step="any"
                                                        value="{{ $value->price }}">
                                                    <input type="hidden" name="products[{{ $c }}][price_id]" multiple
                                                        class="form-control" value="{{ $value->price_id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
@endsection

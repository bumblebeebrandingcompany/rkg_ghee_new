@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="text-color-brown">
                Convert to sales <code>{{ $shop->name }}</code>
            </h1>
        </div>
    </div>

    <div class="row">

        <div class="card card-dashed">

            <div class="card-body">
                
                <form class="row" id="convert_sales" method="POST"
                action="{{ route(prefix_route('store_convert_sales'), $shop->id) }}" enctype="multipart/form-data">

                  
                    @csrf

                    <div class="mb-10 col-md-6">
                        <table class="table">
                            <tr>
                                <td colspan="2">
                                    <h2 class="text-color-brown">SHOP DETAILS</h2>
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Name</strong></td>
                                <td>{{ $shop->name }}</td>
                            </tr>

                            <tr>
                                <td><strong>Contact</strong></td>
                                <td>{{ $shop->contact }}</td>
                            </tr>

                            <tr>
                                <td><strong>Location</strong></td>
                                <td>{{ $shop->location }}</td>
                            </tr>

                            <tr>
                                <td><strong>Pincode</strong></td>
                                <td>{{ $shop->pin_code }}</td>
                            </tr>

                            <tr>
                                <td><strong>GST registration status</strong></td>
                                <td>{{ $shop->gst_registered_string }}</td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="gst_number" class="form-label"><strong>GST Number</strong></label>
                                </td>
                                <td>
                                    <input type="text" name="gst_number" class="form-control form-control-solid"
                                        id="gst_number" placeholder="GST number" value="{{ $shop->gst_number }}" />
                                </td>
                            </tr>

                            <!--  <tr>
                                <td>
                                    <label for="pan_number" class="required form-label"><strong>PAN Number</strong></label>
                                </td>
                                <td>
                                    <input type="text" name="pan_number" required class="form-control form-control-solid" id="pan_number" placeholder="Pan number"/>
                                </td>
                            </tr> -->

                            <tr>
                                <td colspan="2">
                                    <h2 class=" text-color-brown">DOCUMENTS</h2>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="gst_certificate" class="form-label"><strong>GST
                                            Certificate</strong></label>
                                </td>
                                <td>
                                    <input type="file" name="gst_certificate" class="form-control form-control-solid"
                                        id="gst_certificate" />
                                </td>
                            </tr>

                            <!--  <tr>
                                <td>
                                    <label for="pan_certificate" class="required form-label"><strong>PAN Certificate</strong></label>
                                </td>
                                <td>
                                    <input type="file" name="pan_certificate" required class="form-control form-control-solid" id="pan_certificate"/>
                                </td>
                            </tr> -->

                        </table>
                    </div>

                    <div class="mb-10 col-md-6">
                        <table class="table">
                            <tr>
                                <td colspan="2">
                                    <h2 class="text-color-brown">SALES REPRESENTATIVE DETAILS</h2>         
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Name</strong></td>
                                <td>{{ $sales_rep->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Contact</strong></td>
                                <td>{{ $sales_rep->phone_no1 }}</td>
                            </tr>


                            <tr>
                                <td colspan="2">
                                    <h2 class="text-color-brown">PLACE ORDER</h2>
                                </td>
                            </tr>

                            <tr>
                                    <td>
                                        <label for="assigned_distributor_id" class="required form-label"><strong>Assign Distributor/SubStockist:</strong></label>
                                    </td>
                                    <td>
                                        <select id="assigned_distributor_id" required class="form-select"
                                            name="assigned_distributor_id">
                                            <option selected value="">Choose...</option>
                                            @foreach ($distributors as $dist)
                                                <option value="{{ $dist->id }}">{{ $dist->company_name }} - ({{ucwords(str_replace('_', ' ', $dist->role))}})</option>
                                            @endforeach
                                        </select>
                                    </td>
                            </tr>

                        </table>
                    </div>

                    <div class="mt-10 col-md-12">
                        <button type="submit" class="btn btn-primary float-end">Convert</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
@endsection

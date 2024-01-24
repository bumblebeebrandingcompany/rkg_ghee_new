@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="text-color-brown">
                Approve sales <code>{{ $shop->name }}</code>
            </h1>
        </div>
    </div>

    <div class="row">

        <div class="card card-dashed">

            <div class="card-body">

                <form class="row" id="convert_sales" method="POST"
                    action="{{ route('admin.store_approve_sales', $shop->id) }}">
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

                            <tr>
                                <td colspan="2">
                                    <h2 class="text-color-brown">DOCUMENTS</h2>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="gst_certificate" class="form-label"><strong>GST Certificate</strong></label>
                                </td>
                                <td>
                                    @if (!empty($shop->gst_certificate))
                                        <a class="btn btn-primary" href="{{ Storage::url($shop->gst_certificate) }}"
                                            download>Download</a>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="pan_certificate" class="form-label"><strong>PAN Certificate</strong></label>
                                </td>
                                <td>
                                    @if (!empty($shop->pan_certificate))
                                        <a class="btn btn-primary" href="{{ Storage::url($shop->pan_certificate) }}"
                                            download>Download</a>
                                    @endif
                                </td>
                            </tr>

                        </table>
                    </div>

                    <div class="mb-10 col-md-6">
                        <table class="table">
                            <tr>
                                @if ($sales_rep->role == 'sales_rep')
                                    <td colspan="2">
                                        <h2 class="text-color-brown">SALES REPRESENTATIVE DETAILS</h2>
                                    </td>
                                @else
                                    <td colspan="2">
                                        <h2 class="text-color-brown">SALES MAN DETAILS</h2>
                                    </td>
                                @endif
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
                                        <label for="assigned_distributor_id" class="required form-label"><strong>Assign Distributor:</strong></label>
                                    </td>
                                    <td>
                                        <select id="assigned_distributor_id" required class="form-select"
                                            name="assigned_distributor_id">
                                            <option selected value="">Choose...</option>
                                            @foreach ($distributors as $dist)
                                                <option value="{{ $dist->id }}"
                                                    @if ($dist->id == $shop->assigned_distributor_id) selected @endif>
                                                    {{ $dist->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                            
                            </tr>

                        </table>
                    </div>

                    <div class="mt-10 col-md-12">
                        <button type="submit" class="btn btn-success float-end">Approve</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
@endsection

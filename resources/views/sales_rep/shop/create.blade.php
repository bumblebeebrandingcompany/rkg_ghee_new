@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="">
                Add Shop
            </h1>
        </div>
    </div>


    <div class="row">

        <div class="card card-dashed">
            <div class="card-header">
                <h1 class="card-title">Shop Details</h1>
            </div>

            <div class="card-body">
                <form class="row" id="add_shop" method="POST" action="{{ route(prefix_route('shops.store')) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-10 col-md-6">
                        <label for="name" class="required form-label">Name</label>
                        <input type="text" name="name" required class="form-control form-control-solid" id="name"
                            placeholder="Shop Name" />
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="visited_at" class="required form-label">Visited Date</label>
                        <input type="datetime-local" name="visited_at" required class="form-control form-control-solid"
                            id="visited_at" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="contact" class="required form-label">Contact</label>
                        <input type="number" name="contact" required class="form-control form-control-solid" id="contact"
                            placeholder="contact number" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="location" class="required form-label">Location</label>
                        <input type="text" required name="location" class="form-control form-control-solid"
                            id="location" placeholder="location" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="pin_code" class="required form-label">Pincode</label>
                        <input type="pin_code" required name="pin_code" class="form-control form-control-solid"
                            id="pin_code" />
                    </div>

                    <div class="col-md-6 mb-10">
                        <label for="gst_registered" class="required form-label">GST registration status</label>
                        <select id="gst_registered" required class="form-select" name="gst_registered">
                            <option selected>Choose...</option>

                            <option value="1">Registered</option>
                            <option value="0">Not Registered</option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="gst_number" class="form-label">GST Number</label>
                        <input type="text" class="form-control form-control-solid" id="gst_number" name="gst_number" />
                    </div>

                    <hr class="mb-10" />
                    <h2>Proof of visit</h2>
                    <div class="mb-10 col-md-6">
                        <label for="visit_proof_selfie" class="required form-label">Upload Photo</label>
                        <input type="file" class="form-control form-control-solid required" id="visit_proof_selfie"
                            name="visit_proof_selfie" accept="image/*" />
                    </div>

                    <hr class="mb-10" />
                    <h2>Other Details</h2>
                    <div class="mb-10 col-md-6">
                        <label for=" existing_ghee_products" class="form-label">Existing Ghee product</label>
                        <input type="text" class="form-control form-control-solid" id="existing_ghee_products"
                            name="existing_ghee_products" />
                    </div>

                    <div class="col-md-6 mb-10">
                        <label for="type_of_client" class="required form-label">Type of Customer</label>
                        <select id="type_of_client" required class="form-select" name="type_of_client">
                            <option selected>Choose...</option>

                            <option value="bulk_customer">Bulk Customer</option>
                            <option value="retail_customer">Retail Customer</option>
                        </select>
                    </div>

                    <!-- if type  sales_rep = -->

                    <div class="mt-10 col-md-12">
                        <button type="submit" class="btn btn-primary float-end">Submit</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('form#add_shop').validate({
                rules: {
                    contact: {
                        remote: {
                            url: "{{ route('check.shop.exist') }}",
                            type: "post",
                            data: {
                                email: function() {
                                    return $("#contact").val();
                                },
                                user_id: $('input#user_id').val()
                            }
                        }
                    },
                    gst_number: {
                        remote: {
                            url: "{{ route('check.shop.exist') }}",
                            type: "post",
                            data: {
                                gst_number: function() {
                                    return $("#gst_number").val();
                                },
                                user_id: $('input#user_id').val()
                            }
                        }
                    }
                },
                messages: {
                    contact: {
                        remote: '{{ __('validation.unique', ['attribute' => 'contact']) }}'
                    },
                    gst_number: {
                        remote: '{{ __('validation.unique', ['attribute' => 'GST number']) }}'
                    }
                },
                submitHandler: function(form, e) {
                    if ($('form#add_shop').valid()) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@endsection

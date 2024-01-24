@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="">
                Add user @if ($user_type == 'sales_rep')
                    Sales Representative
                @else
                {{ ucwords(str_replace('_', ' ', $user_type))}}
                @endif
            </h1>
        </div>
    </div>


    <div class="row">

        <div class="card card-dashed">
            <div class="card-header">
                <h1 class="card-title">Add user @if ($user_type == 'sales_rep')
                        Sales Representative
                    @else
                    {{ ucwords(str_replace('_', ' ', $user_type))}}
                    @endif
                </h1>
            </div>

            <div class="card-body">

                <form class="row" id="add_user" method="POST" action="{{ route('admin.users.store') }}">

                    <input type="hidden" name="user_type" value="{{ $user_type }}">
                    @csrf

                    <div class="mb-10 col-md-6">
                        <label for="user_name" class="required form-label">Name</label>
                        <input type="text" name="name" required class="form-control form-control-solid" id="user_name"
                            placeholder="User Name" />
                    </div>

                    @if ($user_type == 'admins')
                        <div class="col-md-6 mb-10">
                            <label for="role" class="required form-label">Role</label>
                            <select id="role" required class="form-select" name="role">
                                <option selected>Choose...</option>

                                @foreach ($rkg_admin_roles as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-10 col-md-6">
                        <label for="email" class="required form-label">Email</label>
                        <input type="email" name="email" required class="form-control form-control-solid" id="email"
                            placeholder="Email" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="reference_id" class="form-label">Reference id</label>
                        <input type="text" name="reference_id" class="form-control form-control-solid" id="reference_id"
                            placeholder="Reference id" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="password" class="required form-label">Password</label>
                        <input type="password" required name="password" minlength="6"
                            class="form-control form-control-solid" id="password" placeholder="Password" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="phone_no1" class="required form-label">Phone No.1</label>
                        <input type="number" required name="phone_no1" minlength="10"
                            class="form-control form-control-solid" id="phone_no1" placeholder="Phone or mobile number" />
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="phone_no2" class="form-label">Phone No.2</label>
                        <input type="number" name="phone_no2" minlength="10" class="form-control form-control-solid"
                            id="phone_no2" placeholder="Alternative mobile number" />
                    </div>
                    @if ($user_type == 'admins')
                        <div class="col-md-6 mb-10 d-none" id="sales_div">
                            <label for="assign_to_sales_rep" class="form-label">
                                Assign to sales representative
                            </label>
                            <select id="assign_to_sales_rep" class="multiple_sales" multiple="multiple" class="form-select"
                                name="sales[]">
                                @foreach ($sales_reps as $id => $name)
                                    <option value="{{ $id }}">
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-10 d-none" id="dist_div">
                            <label for="assign_to_distributor" class="form-label">
                                Assign to distributors
                            </label>
                            <select id="distributor" class="multiple_dis" multiple="multiple" class="form-select "
                                name="dist[]">
                                @foreach ($distributor_c_name as $id => $company_name)
                                    <option value="{{ $id }}">
                                        {{ $company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($user_type == 'sales_rep')
                        @if (Auth::user()->role != 'area_manager')
                            <div class="col-md-6 mb-10">
                                <label for="assign_to_areamanager" class="required form-label">
                                    Area Manager
                                </label>
                                <select id="assign_to_areamanager" required class="form-select"
                                    name="assign_to_areamanager">
                                    <option value="">
                                        Please select
                                    </option>
                                    @foreach ($getAreamanager as $r_id => $r_name)
                                        <option value="{{ $r_id }}">
                                            {{ $r_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endif


                    @if (
                        $user_type == 'distributor' ||
                            $user_type == 'wholesaler' ||
                            $user_type == 'super_stockist' ||
                            $user_type == 'sub_stockist')
                        <hr class="mb-10" />
                        <div class="mb-10 col-md-6">
                            <label for="company_name" class="required form-label">Company Name</label>
                            <input type="text" required name="company_name" class="form-control form-control-solid"
                                id="company_name" placeholder="Company/Business or Firm name" />
                        </div>


                        @if ($user_type == 'sub_stockist')
                            <div class="col-md-6 mb-10">
                                <label for="assign_to_super_stockist" class="required form-label">
                                    Assign to super stockist
                                </label>
                                <select id="assign_to_super_stockist" class="form-select" required
                                    name="assign_to_super_stockist">
                                    <option value="">
                                        Please select
                                    </option>
                                    @foreach ($super_stockists as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if ($user_type == 'distributor' || $user_type == 'sub_stockist')
                            <div class="col-md-6 mb-10">
                                <label for="assign_to_sales_rep" class="form-label">
                                    Assign to sales representative
                                </label>
                                <select id="assign_to_sales_rep" class="form-select" name="assign_to_sales_rep">
                                    <option value="">
                                        Please select
                                    </option>
                                    @foreach ($sales_reps as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if ($user_type == 'distributor' || $user_type == 'wholesaler' || $user_type == 'sub_stockist')
                            <div class="col-md-6 mb-10">
                                <label for="assign_to_areamanager" class="required form-label">
                                    Assign to areamanager
                                </label>
                                <select id="assign_to_areamanager" required class="form-select"
                                    name="assign_to_areamanager">
                                    <option value="">
                                        Please select
                                    </option>
                                    @foreach ($getAreamanager as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-10">
                                <label for="assign_to_areamanager_2" class=" form-label">
                                    Assign to areamanager 2
                                </label>
                                <select id="assign_to_areamanager_2" class="form-select" name="assign_to_areamanager_2">
                                    <option value="">
                                        Please select
                                    </option>
                                    @foreach ($getAreamanager as $id => $name)
                                        <option value="{{ $id }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-info">Area manager 2 get email & sms for order</span>
                            </div>
                        @endif
                        <div class="col-12 mb-10">
                            <label for="address_line_1" class="required form-label">Address Line 1</label>
                            <input type="text" required name="address_line_1" class="form-control form-control-solid"
                                id="address_line_1" placeholder="1234 Main St">
                        </div>
                        <div class="col-12 mb-10">
                            <label for="address_line_2" class="form-label">Address 2</label>
                            <input type="text" name="address_line_2" class="form-control form-control-solid"
                                id="address_line_2" placeholder="Apartment, studio, or floor">
                        </div>
                        <div class="col-md-6 mb-10">
                            <label for="address_city" class="required form-label">Place</label>
                            <input type="text" required name="address_city" class="form-control form-control-solid"
                                id="address_city">
                        </div>
                        <div class="col-md-4 mb-10">
                            <label for="address_state" class="required form-label">State</label>
                            <select id="address_state" required class="form-select" name="address_state">
                                <option selected value="">Choose...</option>
                                @foreach ($states_list as $state)
                                    <option>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-10">
                            <label for="address_zip" class="required form-label">Zip</label>
                            <input type="text" required name="address_zip" class="form-control form-control-solid"
                                id="address_zip">
                        </div>


                        <div class="mb-10 col-md-6">
                            <label for="gst_number" class="form-label">GST Number</label>
                            <input type="text" class="form-control form-control-solid"id="gst_number"
                                name="gst_number" />
                        </div>

                        <div class="mb-10 col-md-6">
                            <label for="pan_number" class="form-label required">PAN Number</label>
                            <input type="text" class="form-control form-control-solid" required id="pan_number"
                                name="pan_number" />
                        </div>

                        <div class="mb-10 col-md-6">
                            <label for="target_tonnage" class="required form-label">Target Tonnage for current financial
                                year</label>
                            <input type="number" class="form-control form-control-solid" id="target_tonnage" required
                                name="target_tonnage" />
                            <div class="form-text">{{ $current_fy_date['start'] }} to {{ $current_fy_date['end'] }}</div>
                        </div>

                        @if ($user_type == 'distributor' || $user_type == 'wholesaler')
                            <div class="mb-10 col-md-6">
                                <label for="distributor_discount" class="required form-label">Discount (%)</label>
                                <input type="number" class="form-control form-control-solid" id="distributor_discount"
                                    required name="distributor_discount" />
                            </div>
                        @endif




                        <div class="mb-10 col-md-6">
                            <label for="rewards_card_number" class="required form-label">Rewards Card Number</label>
                            <input type="text" class="form-control form-control-solid" id="rewards_card_number"
                                required name="rewards_card_number" />
                        </div>
                    @endif

                    @if ($user_type == 'distributor')
                        <div class="mt-12 col-md-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_super_stockist"
                                    id="is_super_stockist" value="1" />
                                <label class="form-check-label" for="is_super_stockist">Is Super stockist</label>
                            </div>
                        </div>
                    @endif

                    <!-- if type  sales_rep = -->

                    <div class="mt-10 col-md-12">
                        <button type="submit" id="user_sub_btn" class="btn btn-primary float-end">Submit</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {

            $(".multiple_sales").select2({
                placeholder: "Select sales representative"
            })
            $(".multiple_dis").select2({
                placeholder: "Select distributors"
            })

            $('form#add_user').validate({
                rules: {
                    email: {
                        email: true,
                        remote: {
                            url: "{{ route('check.email.exist') }}",
                            type: "post",
                            data: {
                                email: function() {
                                    return $("#email").val();
                                },
                                user_id: $('input#user_id').val()
                            }
                        }
                    },
                    reference_id: {
                        remote: {
                            url: "{{ route('check.reference_id.exist') }}",
                            type: "post",
                            data: {
                                reference_id: function() {
                                    return $("#reference_id").val();
                                },
                                user_id: $('input#user_id').val()
                            }
                        }
                    }
                },
                messages: {
                    email: {
                        remote: '{{ __('validation.unique', ['attribute' => __('messages.email')]) }}'
                    },
                    reference_id: {
                        remote: '{{ __('validation.unique', ['attribute' => __('reference_id')]) }}'
                    }
                },
                submitHandler: function(form, e) {
                    $("#user_sub_btn").attr("disabled", true);
                    if ($('form#add_user').valid()) {
                        form.submit();
                    }
                }
            });

            $('#role').change(function() {
                if ($(this).val() == 'area_manager') {
                    $('#sales_div').removeClass('d-none');
                    $('#dist_div').removeClass('d-none');
                } else {
                    $('#sales_div').addClass('d-none');
                    $('#dist_div').addClass('d-none');
                }
            })
        });
    </script>
@endsection

@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h1 class="">
            Edit user @if($user_type == 'sales_rep') Sales Representative @else {{ ucwords(str_replace('_', ' ', $user_type))}} @endif
        </h1>
    </div>
</div>


<div class="row">

    <div class="card card-dashed">
        <div class="card-header">
            <h1 class="card-title">Edit user @if($user_type == 'sales_rep') Sales Representative @else{{ ucwords(str_replace('_', ' ', $user_type))}} @endif</h1>
        </div>

        <div class="card-body">

            <form class="row" id="edit_user" method="POST" action="{{route('admin.users.update', ['user' => $user->id])}}">
                <input type="hidden" name="user_type" value="{{$user_type}}">
                <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                @csrf
                @method('PUT')
                <div class="mb-10 col-md-6">
                    <label for="user_name" class="required form-label">Name</label>
                    <input type="text" name="name" required class="form-control form-control-solid" id="user_name" placeholder="User Name" value="{{$user->name}}"/>
                </div>
                @if($user_type == 'admins')
                    <div class="col-md-6 mb-10">
                        <label for="role" class="required form-label">Role</label>
                        <select id="role" onchange="change()" required class="form-select" name="role">
                            @foreach($rkg_admin_roles as $k => $v)
                                <option value="{{$k}}" 
                                    @if($user->role == $k)
                                        selected
                                    @endif
                                >{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <div class="mb-10 col-md-6">
                    <label for="email" class="required form-label">Email</label>
                    <input type="email" name="email" required class="form-control form-control-solid" id="email" placeholder="Email" value="{{$user->email}}"/>
                </div>

                <div class="mb-10 col-md-6">
                    <label for="reference_id" class="required form-label">Reference id</label>
                    <input type="text" name="reference_id" value="{{$user->reference_id}}" class="form-control form-control-solid" id="reference_id" required placeholder="Reference id"/>
                </div>

                <div class="mb-10">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" minlength="6" class="form-control form-control-solid" id="password" placeholder="Password"/>
                    <small class="form-text text-muted">
                        Keep it blank, if you don't want to change.
                    </small>
                </div>

                <div class="mb-10 col-md-6">
                    <label for="phone_no1" class="required form-label">Phone No.1</label>
                    <input type="number" required name="phone_no1" minlength="10" class="form-control form-control-solid" id="phone_no1" placeholder="Phone or mobile number" value="{{$user->phone_no1}}"/>
                </div>

                <div class="mb-10 col-md-6">
                    <label for="phone_no2" class="form-label">Phone No.2</label>
                    <input type="number" name="phone_no2" minlength="10" class="form-control form-control-solid" id="phone_no2" placeholder="Alternative mobile number" value="{{$user->phone_no2}}"/>
                </div>
                 @if($user_type == 'sales_rep')
                    @if(Auth::user()->role != 'area_manager')
                        <div class="col-md-6 mb-10">
                            <label for="assign_to_areamanager" class="required form-label">
                                 Area Manager
                            </label>
                            <select id="assign_to_areamanager" required class="form-select" name="assign_to_areamanager">
                                <option value="">
                                    Please select
                                </option>
                                @foreach($getAreamanager as $r_id => $r_name)
                                    <option {{ $user->assign_to_areamanager == $r_id ? 'selected' : '' }} value="{{$r_id}}">
                                        {{$r_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endif
                @if($user_type == 'admins')
                <div class="col-md-6 mb-10 d-none" id="sales_div">
                        <label for="assign_to_sales_rep" class="form-label">
                            Assign to sales representative
                        </label>
                        <select id="assign_to_sales_rep" class="multiple_sales"  multiple="multiple"  class="form-select" name="sales[]">
                            @foreach($sales_reps as $s_id => $sales_name)
                                <option {{ in_array($s_id, $sales_and_dist) ? 'selected' : '' }} value="{{$s_id}}">
                                    {{$sales_name}}
                                </option>
                            @endforeach
                        </select>
                </div>
                <div class="col-md-6 mb-10 d-none" id="dist_div">
                        <label for="assign_to_distributor" class="form-label">
                            Assign to distributors
                        </label>
                        <select id="assign_to_distributor" class="multiple_dis"  multiple="multiple"  class="form-select" name="dist[]">
                            @foreach($distributor_c_name as $d_id => $d_name)
                                <option {{ in_array($d_id, $sales_and_dist) ? 'selected' : '' }} value="{{$d_id}}">
                                    {{$d_name}}
                                </option>
                            @endforeach
                        </select>
                </div>
                
                @endif
                @if($user_type == 'distributor' || $user_type == 'wholesaler' || $user_type == 'super_stockist' || $user_type == 'sub_stockist')
                    <hr class="mb-10" />
                    <div class="mb-10 col-md-6">
                        <label for="company_name" class="required form-label">Company Name</label>
                        <input type="text" required name="company_name" class="form-control form-control-solid" id="company_name" placeholder="Company/Business or Firm name" value="{{$user->company_name}}"/>
                    </div>
                    @if ($user_type == 'sub_stockist')
                    <div class="col-md-6 mb-10">
                        <label for="assign_to_super_stockist" class="required form-label">
                            Assign to super stockist
                        </label>
                        <select id="assign_to_super_stockist" class="form-select" required name="assign_to_super_stockist">
                            <option value="">
                                Please select
                            </option>
                            @foreach ($super_stockists as $id => $name)
                                <option value="{{ $id }}"
                                @if($user->assign_to_super_stockist == $id)
                                selected
                            @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if($user_type == 'distributor' || $user_type == 'sub_stockist')
                        <div class="col-md-6 mb-10">
                            <label for="assign_to_sales_rep" class=" form-label">
                                Assign to sales representative
                            </label>
                            <select id="assign_to_sales_rep" class="form-select" name="assign_to_sales_rep">
                                <option value="">
                                    Please select
                                </option>
                                @foreach($sales_reps as $id => $name)
                                    <option value="{{$id}}"
                                        @if($user->assign_to_sales_rep == $id)
                                            selected
                                        @endif>
                                        {{$name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if($user_type == 'distributor' || $user_type == 'wholesaler' || $user_type == 'sub_stockist')
                    <div class="col-md-6 mb-10">
                        <label for="assign_to_areamanager" class="required form-label">
                            Assign to areamanager
                        </label>
                        <select id="assign_to_areamanager" required class="form-select" name="assign_to_areamanager">
                            <option value="">
                                Please select
                            </option>
                            @foreach($getAreamanager as $id => $name)
                                <option {{ $user->assign_to_areamanager == $id ? 'selected':''}} value="{{$id}}">
                                    {{$name}}
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
                            @foreach($getAreamanager as $id => $name)
                                <option value="{{$id}}" {{ $user->assign_to_areamanager_2 == $id ? 'selected':''}}  >
                                    {{$name}}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-info">Area manager 2 get email & sms for order</span>
                    </div> 
                    @endif
                    <div class="col-12 mb-10">
                        <label for="address_line_1" class="required form-label">Address Line 1</label>
                        <input type="text" required name="address_line_1" class="form-control form-control-solid" id="address_line_1" placeholder="1234 Main St" value="{{$user->address_line_1}}">
                    </div>
                    <div class="col-12 mb-10">
                        <label for="address_line_2" class="form-label">Address 2</label>
                        <input type="text" name="address_line_2" class="form-control form-control-solid" id="address_line_2" placeholder="Apartment, studio, or floor" value="{{$user->address_line_2}}">
                    </div>
                    <div class="col-md-6 mb-10">
                        <label for="address_city" class="required form-label">Place</label>
                        <input type="text" required name="address_city" class="form-control form-control-solid" id="address_city" value="{{$user->address_city}}">
                    </div>
                    <div class="col-md-4 mb-10">
                        <label for="address_state" class="required form-label">State</label>
                        <select id="address_state" required class="form-select" name="address_state">
                            <option selected value="">Choose...</option>
                            @foreach($states_list as $state)
                                <option 
                                    @if($user->address_state == $state)
                                        selected
                                    @endif>
                                    {{$state}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-10">
                        <label for="address_zip" class="required form-label">Zip</label>
                        <input type="text" required name="address_zip" class="form-control form-control-solid" id="address_zip" value="{{$user->address_zip}}">
                    </div>


                    <div class="mb-10 col-md-6">
                        <label for="gst_number" class="form-label">GST Number</label>
                        <input type="text" class="form-control form-control-solid" id="gst_number" name="gst_number" value="{{$user->gst_number}}"/>
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="pan_number" class="form-label required">PAN Number</label>
                        <input type="text" required class="form-control form-control-solid" id="pan_number" name="pan_number" value="{{$user->pan_number}}"/>
                    </div>

                    @if ($user_type == 'distributor' || $user_type == 'wholesaler')
                    <div class="mb-10 col-md-6">
                        <label for="distributor_discount" class="required form-label">Discount (%)</label>
                        <input type="number" class="form-control form-control-solid" id="distributor_discount" required name="distributor_discount" value="{{$user->distributor_discount}}"/>
                    </div>   
                    @endif

                   



                    <div class="mb-10 col-md-6">
                        <label for="rewards_card_number" class="required form-label">Rewards Card Number</label>
                        <input type="text" class="form-control form-control-solid" id="rewards_card_number" required name="rewards_card_number" value="{{$user->rewards_card_number}}"/>
                    </div>
                @endif

                @if ($user_type == 'distributor')
                <div class="mt-12 col-md-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" {{ $user->is_super_stockist == 1 ? 'checked': '' }} type="checkbox" name="is_super_stockist"
                            id="is_super_stockist" value="1" />
                        <label class="form-check-label" for="is_super_stockist">Is Super stockist</label>
                    </div>
                </div>
            @endif

                <!-- if type  sales_rep = -->

                <div class="mt-10 col-md-12">
                    <button type="submit" class="btn btn-primary float-end">Update</button>
                </div>
            </form>
        </div>

        
    </div>
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
         $(".multiple_sales").select2({
          placeholder: "Select sales representative"
        })
        $(".multiple_dis").select2({
          placeholder: "Select distributors"
        })

        $('form#edit_user').validate({
            rules: {
                email: {
                    email: true,
                    remote: {
                        url: "{{route('check.email.exist')}}",
                        type: "post",
                        data: {
                            email: function() {
                                return $( "#email" ).val();
                            },
                            user_id: $('input#user_id').val()
                        }
                    }
                },
                reference_id: {
                    remote: {
                        url: "{{route('check.reference_id.exist')}}",
                        type: "post",
                        data: {
                            reference_id: function() {
                                return $( "#reference_id" ).val();
                            },
                            user_id: $('input#user_id').val()
                        }
                    }
                }
            },
            messages: {
                email: {
                    remote: '{{ __("validation.unique", ["attribute" => __("messages.email")]) }}'
                },
                 reference_id: {
                    remote: '{{ __("validation.unique", ["attribute" => __("reference_id")]) }}'
                }
            },
            submitHandler: function(form, e) {
                if ($('form#edit_user').valid()) {
                    form.submit();
                }
            }
        });
    });
     function change(){
            if($('#role').val() == 'area_manager'){
                $('#sales_div').removeClass('d-none');
                $('#dist_div').removeClass('d-none');
            }else{
                $('#sales_div').addClass('d-none');
                $('#dist_div').addClass('d-none');
            }
        } 

        change();
</script>
@endsection
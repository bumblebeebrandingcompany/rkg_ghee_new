@extends('layout.app')
@section('content')
<div class="row">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="col-md-6">
        <h1 class="">
            Edit Profile
        </h1>
    </div>
</div>
<div class="row">
    <div class="card card-dashed">
        <div class="card-header">
            <h1 class="card-title">
                Edit Profile
            </h1>
        </div>
        <div class="card-body">
            <form class="row" id="edit_profile" method="POST" action="{{route('update.profile')}}">
                @csrf
                @method('PUT')
                <input type="hidden" id="user_id" value="{{$user->id}}">

                <div class="mb-10 col-md-12">
                    <label for="user_name" class="required form-label">Name</label>
                    <input type="text" name="name" required class="form-control form-control-solid" id="user_name" placeholder="User Name" value="{{$user->name}}"/>
                </div>
                <div class="mb-10">
                    <label for="email" class="required form-label">Email</label>
                    <input type="email" name="email" required class="form-control form-control-solid" id="email" placeholder="Email" value="{{$user->email}}"/>
                </div>

                <div class="mb-10">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" minlength="6" class="form-control form-control-solid" id="email" placeholder="Password"/>
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

                @if($user->role == 'distributor')
                    <hr class="mb-10" />
                    <div class="mb-10 col-md-6">
                        <label for="company_name" class="required form-label">Company Name</label>
                        <input type="text" required name="company_name" class="form-control form-control-solid" id="company_name" placeholder="Company/Business or Firm name" value="{{$user->company_name}}"/>
                    </div>

                    
                    <div class="col-12 mb-10">
                        <label for="address_line_1" class="required form-label">Address Line 1</label>
                        <input type="text" required name="address_line_1" class="form-control form-control-solid" id="address_line_1" placeholder="1234 Main St" value="{{$user->address_line_1}}">
                    </div>
                    <div class="col-12 mb-10">
                        <label for="address_line_2" class="form-label">Address 2</label>
                        <input type="text" name="address_line_2" class="form-control form-control-solid" id="address_line_2" placeholder="Apartment, studio, or floor" value="{{$user->address_line_2}}">
                    </div>
                    <div class="col-md-6 mb-10">
                        <label for="address_city" class="required form-label">City</label>
                        <input type="text" required name="address_city" class="form-control form-control-solid" id="address_city" value="{{$user->address_city}}">
                    </div>
                    <div class="col-md-4 mb-10">
                        <label for="address_state" class="required form-label">State</label>
                        <select id="address_state" required class="form-select" name="address_state">
                            <option selected>Choose...</option>
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
                        <input type="text" class="form-control form-control-solid" id="gst_number" readonly name="gst_number" value="{{$user->gst_number}}"/>
                        <small class="form-text text-muted">
                            Contact RKG admin to change it.
                        </small>
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="pan_number" class="form-label">PAN Number</label>
                        <input type="text" class="form-control form-control-solid" id="pan_number" readonly name="pan_number" value="{{$user->pan_number}}"/>

                        <small class="form-text text-muted">
                            Contact RKG admin to change it.
                        </small>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="rewards_card_number" class="form-label">Rewards Card Number</label>
                        <input type="text" class="form-control form-control-solid" id="rewards_card_number" readonly name="rewards_card_number" value="{{$user->rewards_card_number}}"/>

                        <small class="form-text text-muted">
                            Cannot be changed
                        </small>
                    </div>
                @endif
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
        $('form#edit_profile').validate({
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
                }
            },
            messages: {
                email: {
                    remote: '{{ __("validation.unique", ["attribute" => __("messages.email")]) }}'
                }
            },
            submitHandler: function(form, e) {
                if ($('form#edit_profile').valid()) {
                    form.submit();
                }
            }
        });
    });
</script>
@endsection
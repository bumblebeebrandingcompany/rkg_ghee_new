@extends('layout.auth')
@section('content')

@if (session('status'))
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="alert alert-success alert-light-primary fade show mb-5" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">
                    {{ session('status') }}
                </div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
                    
<form class="form w-100" method="POST" action="{{ route('login') }}" id="login_form">
    @csrf
    <!--begin::Heading-->
    <div class="text-center mb-10">
        <!--begin::Title--> 
        <center><img src="{{asset('assets/logos.webp')}}" alt="RKG" width="50%"></center><br><br>
        <h1 class="text-dark mb-3" style="color: #4f1311!important;">Sign In to RKG Rewards</h1>
        <!--end::Title-->
        <!--begin::Link-->
        <!-- <div class="text-gray-400 fw-bold fs-4">New Here? 
        <a href="sign-up.html" class="link-primary fw-bolder" style="color: #000!important;">Create an Account</a></div> -->
        <!--end::Link-->
    </div>
    <!--begin::Heading-->
    <!--begin::Input group-->
    <div class="fv-row mb-10">
        <!--begin::Label-->
        <label class="form-label fs-6 fw-bolder text-dark">Email</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input class="form-control @error('email') is-invalid @enderror form-control-lg form-control-solid" type="email" name="email" autocomplete="off" id="email" value="{{old('email')}}" required autofocus placeholder="{{ __('E-Mail Address') }}"/>
        
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="fv-row mb-10">
        <!--begin::Wrapper-->
        <div class="d-flex flex-stack mb-2">
            <!--begin::Label-->
            <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
            <!--end::Label-->
            <!--begin::Link-->
            <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder" style="color: #000!important;">Forgot Password ?</a>
            <!--end::Link-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Input-->
        <input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" type="password" name="password"  required autocomplete="current-password" placeholder="{{ __('Password') }}"/>

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <!--end::Input-->
    </div>
    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="text-center">
        <!--begin::Submit button-->
        <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5" style="background-color: #4f1311;">
            <span class="indicator-label">Continue</span>
            <span class="indicator-progress">Please wait... 
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
        <!--end::Submit button-->
        <!--begin::Separator-->
        
        <!--end::Separator-->
        <!--begin::Google link-->
        
    </div>
    <!--end::Actions-->
</form>
@endsection
@section('javascript')
<script>
  $(function(){
    $("form#login_form").validate();
  });
</script>
@endsection
@extends('layout.auth')

@section('content')
<form method="POST" action="{{ route('password.update') }}" id="reset_password_form" class="form w-100" novalidate="novalidate">
    @csrf
    <input type="hidden" name="token" value="{{ $request->token }}">
    <!--begin::Heading-->
    <div class="text-center mb-10">
        <center><img src="{{asset('assets/logos.webp')}}" alt="RKG" width="50%"></center><br><br>
        <!--begin::Title-->
        <h1 class="text-dark mb-3">{{ __('Reset Password') }}</h1>

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

        <!--end::Title-->
        <!--begin::Link-->
        <!-- <div class="text-gray-400 fw-bold fs-4">Enter your email to reset your password.</div> -->
        <!--end::Link-->
    </div>
    <!--begin::Heading-->
    <!--begin::Input group-->
    <div class="fv-row mb-10">
        <label class="form-label fw-bolder text-gray-900 fs-6">{{ __('E-Mail Address') }}</label>
        <input class="form-control form-control-solid @error('email') is-invalid @enderror" type="email" placeholder="{{ __('E-Mail Address') }}" name="email" id="email" autocomplete="off" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus/>

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <div class="fv-row mb-10">
        <label class="form-label fw-bolder text-gray-900 fs-6">{{ __('Password') }}</label>
        <input class="form-control form-control-solid @error('password') is-invalid @enderror" type="password" placeholder="{{ __('Password') }}" name="password" id="password" autocomplete="off" required autocomplete="new-password"/>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <div class="fv-row mb-10">
        <label class="form-label fw-bolder text-gray-900 fs-6">{{ __('Confirm Password') }}</label>
        <input class="form-control form-control-solid" type="password" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" id="password-confirm" autocomplete="new-password" required autocomplete="new-password" autofocus/>

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <!--end::Input group-->
    <!--begin::Actions-->
    <div class="d-flex flex-wrap justify-content-center pb-lg-0">
        <button type="submit" id="kt_password_reset_submit" class="btn btn-lg btn-primary fw-bolder me-4 button button1" style="background-color: #4f1311;">
            <span class="indicator-label">{{ __('Reset Password') }}</span>
            <span class="indicator-progress">Please wait... 
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
        <a href="{{url('/login')}}" class="btn btn-lg btn-light-primary fw-bolder" style="color:#4f1311; " >Cancel</a>
    </div>
    <!--end::Actions-->
</form>
@endsection
@section('javascript')
<script>
  $(function(){
    $("form#reset_password_form").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
            },
            password_confirmation: {
                equalTo: '#password',
            },
        },
        messages: {
            password: {
                minlength: 'Password should be minimum 8 characters',
            },
            password_confirmation: {
                equalTo: 'Password Mismatched',
            },
        }
    });
  });
</script>
@endsection
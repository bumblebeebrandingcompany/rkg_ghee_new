<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('/fav-icon.png') }}" type="image/x-icon">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script type="application/javascript">
        //common App variable to be used in front-end
        var APP = {};
        APP.APP_NAME = '{{config('app.name')}}';
        APP.APP_URL = '{{config('app.url')}}';
    </script>

    @php
        $asset_v = 1;
    @endphp
    @include('layout.partials.css')
    <style>
        @font-face {
  
  src:  url("{{asset('assets/fonts/Clan-News.ttf')}}");
  font-family: Clan-News;
  
}

        body {
            font-family: Clan-News;
            font-size: 15px;
            line-height: 1.75;
            margin: 0;
            text-align: left;
            background-color: #fff;
            font-weight: 400;
            color: #6c757d;
        }

    @media screen and (max-width: 500px) {

        .imgall{
            width: 100%!important;
        }


        .imgallhight{
            width: 100%!important;
            height: 1200!important;
        }
    }

        /*.header-fixed.subheader-fixed.subheader-enabled .wrapper {
            padding-top: 20px;
        }*/
    </style>
    @yield('css')
</head>
<body id="kt_body" class="bg-body">
    

    <div class="d-flex flex-column flex-root">
            <!--begin::Authentication - Sign-in -->
            <div class="d-flex flex-column flex-lg-row flex-column-fluid">
                <!--begin::Aside-->
                <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: #F2C98A">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                        <!--begin::Content-->
                        <div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
                            <!--begin::Logo-->
                            <a href="{{url('/')}}" class="py-9 mb-5">
                                <img src="{{asset('assets/logos.webp')}}" alt="RKG" class="imgall">
                            </a>
                            <!--end::Logo-->
                            <!--begin::Title-->
                            <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #4f1311; font-family: Clan-News;">Welcome to 
                                <b>RKG Rewards</b></h1>
                            <img src="{{asset('assets/rkgvistingcard.webp')}}" alt="RKG">

                            <!--end::Title-->
                            <!--begin::Description-->
                            
                            <!--end::Description-->
                        </div>
                        <!--end::Content-->
                        <!--begin::Illustration-->
                        <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-position-y-bottom min-h-100px min-h-lg-350px imgallhight" style="background-image: url({{asset('assets/rkgbg9.webp')}}); width: 100%!important;  height: 350px;" ></div>
                        <!--end::Illustration-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Aside-->
                <!--begin::Body-->
                <div class="d-flex flex-column flex-lg-row-fluid py-10">
                    <!--begin::Content-->
                    <div class="d-flex flex-center flex-column flex-column-fluid">
                        <!--begin::Wrapper-->
                        <div class="w-lg-500px p-10 p-lg-15 mx-auto">
                            <!--begin::Form-->
                            @yield('content')
                            <!--end::Form-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Content-->
                    <!--begin::Footer-->
                    <div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
                        <!--begin::Links-->
                        
                        <!--end::Links-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Authentication - Sign-in-->
        </div>

    <!--end::Global Config-->
    @include('layout.partials.javascript')
    @yield('javascript')

</body>
</html>

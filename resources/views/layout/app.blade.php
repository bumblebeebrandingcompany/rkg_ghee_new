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
        $asset_v = 2;
    @endphp
    @include('layout.partials.css')
    <style>
        /*.header-fixed.subheader-fixed.subheader-enabled .wrapper {
            padding-top: 20px;
        }*/
    </style>
    @yield('css')
</head>


<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed @auth aside-fixed @endauth">
    
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Aside-->
            @auth
                @include('layout.partials.sidebar')
            @endauth
            <!--end::Aside-->
            <!--begin::Wrapper-->
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @auth
                    @include('layout.partials.header')
                @endauth
                @if (session('status'))
                    <input type="hidden" id="toastr_status" data-status="{{ session('status.success') }}" data-msg="{{ session('status.msg') }}">
                @endif
                <!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    {{-- @include('layout.partials.sub_header') --}}
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            @yield('content')
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                @auth
                    @include('layout.partials.footer')
                @endauth
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
    <!--end::Global Config-->
    @include('layout.partials.javascript')
    @yield('javascript')

    <div class="modal fade" tabindex="-1" id="global-modal">
    </div>

</body>
</html>

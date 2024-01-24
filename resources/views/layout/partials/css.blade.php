{{-- vendor --}}
<link href="{{ asset('assets/plugins/global/plugins.bundle.css?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.bundle.css?v=' . $asset_v) }}" rel="stylesheet">
<!-- <link href="{{ asset('assets/css/themes/layout/header/base/light.css?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('assets/css/themes/layout/header/menu/light.css?v=' . $asset_v) }}" rel="stylesheet"> -->
<!-- <link href="{{ asset('assets/css/themes/layout/brand/dark.css?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('assets/css/themes/layout/aside/dark.css?v=' . $asset_v) }}" rel="stylesheet"> -->
<link href="{{ asset('plugins/dropzone/_dropzone.scss?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('css/custom.css?v=' . $asset_v) }}" rel="stylesheet">
<link href="{{ asset('plugins/fontawesome/css/all.css?v=' . $asset_v) }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.css?v={{$asset_v}}"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css?v={{$asset_v}}"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.css"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.error{
		color: #ff0000;
	}

	.cursor-pointer{
		cursor: pointer;
	}

	.custom-group-text{
		padding: 8px;
    	background-color: white;
    	font-weight: 600;
	}

	.plus-icon{
		padding-left: 10px;
		padding-right: 10px;
	}
	.plus-icon > i{
		color: green !important;
		font-size: 18px;
		font-weight: bolder;
	}

	.minus-icon{
		padding-left: 10px;
		padding-right: 10px;
	}
	.minus-icon > i{
		color: red !important;
		font-size: 18px;
		font-weight: bolder;
	}

	.color-yellow{
		background-color: #FFCB05 !important;
	}
	.text-color-yellow{
		color: #FFCB05 !important;
	}

	.color-brown{
		background-color: #4F1311 !important;
	}
	.text-color-brown{
		color: #4F1311 !important;
	}

	/* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
</style>
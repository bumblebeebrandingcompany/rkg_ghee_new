@extends('layout.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h1 class="">
			Welcome {{$user->name}}
			</h1>
		</div>
	</div>
</div>
@endsection
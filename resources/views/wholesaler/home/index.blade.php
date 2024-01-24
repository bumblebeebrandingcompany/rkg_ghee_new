@extends('layout.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="">
                Welcome {{$user->company_name}}
            </h1>
        </div>
    </div>
    @includeIf('wholesaler.dashboard.dashboard')
</div>
@endsection
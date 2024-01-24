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
@if($user->role=='area_manager')
<div class="row mt-5">
	<div class="col-md-6 d-flex">
		<div class="card shadow-sm flex-fill">
			<div class="card-header">
				<!--begin::Title-->
				<h3 class="card-title">
				<span class="fw-bolder mb-2 text-dark">
					Top  distributors by volume
				</span>
				</h3>
			</div>
			<!--begin::Card body-->
			<div class="card-body text-center">
				<table class="table table-condensed text-start w-100">
					<thead class="bg-light">
						<tr style="font-weight:bold">
							<th>Distr Id</th>
							<th>Trade Name</th>
							<th>Place</th>
							<th>Ton(s)</th>
						</tr>
					</thead>
					<tbody>
						@foreach($distributor_volume as $disp)
						<tr>
							<td>
								{{ $disp->reference_id }}
							</td>
							<td>
								{{ $disp->company_name }}
							</td>
							<td>
								{{ $disp->address_line_1 }}
							</td>
							<td>
								{{ number_format($disp->total_weight/1000,4) }}	{{ 'Ton(s)'}}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6 d-flex">
		<div class="card shadow-sm flex-fill">
			<div class="card-header">
				<!--begin::Title-->
				<h3 class="card-title">
				<span class="fw-bolder mb-2 text-dark">
					Top distributors by points
				</span>
				</h3>
			</div>
			<!--begin::Card body-->
			<div class="card-body text-center">
				<table class="table table-condensed text-start">
					<thead class="bg-light">
						<tr style="font-weight:bold">
							<th>Distr Id</th>
							<th>Trade Name</th>
							<th>Place</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
						@foreach($distributor_point as $disp)
						<tr>
							<td>
								{{ $disp->reference_id }}
							</td>
							<td>
								{{ $disp->company_name }}
							</td>
							<td>
								{{ $disp->address_line_1 }}
							</td>
							<td>
								{{ $disp->total_points }}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row mt-5">
	<div class="col-md-6 d-flex">
		<div class="card shadow-sm flex-fill">
			<div class="card-header">
				<!--begin::Title-->
				<h3 class="card-title">
				<span class="fw-bolder mb-2 text-dark">
					Top sales representative by Conversion
				</span>
				</h3>
			</div>
			<!--begin::Card body-->
			<div class="card-body text-center">
				<table class="table table-condensed text-start">
					<thead class="bg-light">
						<tr style="font-weight:bold">
							<th>Sales Reps Id</th>
							<th>Email</th>
							<th>Phone No</th>
							<th> Shop Converted</th>
						</tr>
					</thead>
					<tbody>
						@foreach($sale_convert as $sals)
						<tr>
							<td>
								{{ $sals->reference_id }}
							</td>
							<td>
								{{ $sals->email }}
							</td>
							<td>
								{{ $sals->phone_no1 }}
							</td>
							<td>
								{{ $sals->total }}
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6 d-flex">
		<div class="card shadow-sm flex-fill">
			<div class="card-header">
				<!--begin::Title-->
				<h3 class="card-title">
				<span class="fw-bolder mb-2 text-dark">
					Top sales representative by points
				</span>
				</h3>
			</div>
			<!--begin::Card body-->
			<div class="card-body text-center">
				<table class="table table-condensed text-start">
					<thead class="bg-light">
						<tr style="font-weight:bold">
							<th>Sales Rep Id</th>
							<th>Email</th>
							<th>Phone No</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
						@foreach($sale_point as $sals)
						<tr>
							<td>
								{{ $sals->reference_id }}
							</td>
							<td>
								{{ $sals->email }}
							</td>
							<td>
								{{ $sals->phone_no1 }}
							</td>
							<td>
								{{ $sals->points }}
							</td> 
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
@endsection
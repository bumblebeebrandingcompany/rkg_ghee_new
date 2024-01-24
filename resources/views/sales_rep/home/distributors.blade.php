@extends('layout.app')
@section('content')


<div class="row g-5 g-xl-12">
	@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
	@endif
	<div class="col-xl-12">
		<div class="card card-xl-stretch mb-xl-12">
			<div class="card-header border-0 pt-5">
				<h1 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Your {{ ucwords(str_replace('_', ' ', $type)) }}s</span>
                </h1>
			</div>
			<div class="card-body py-3">
				
				<div class="row">
					<div class="table-responsive">
						<table class="table table-rounded table-striped border gy-7 gs-7">
							<thead>
								<tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
									<th>Company Name</th>
									<th>Address</th>
									<th>Phone No.1</th>
									<th>Phone No.2</th>
									<th>Reference id</th>
									<th>Discount(%)</th>
									<th>Rewards Card Number</th>
									<th>GST Number</th>
									<th>PAN Number</th>
								</tr>
							</thead>
							<tbody>
								@foreach($distributors as $distributor)
									<tr>
										<td>{{$distributor->company_name}}</td>
										<td>
											{!!$distributor->address_line_1 . '<br/>' . $distributor->address_line_2 . '<br/>' . $distributor->address_city . ' ' . $distributor->address_state . ' ' . $distributor->address_zip!!}
										</td>
										<td>
											{{$distributor->phone_no1}}
										</td>
										<td>
											{{$distributor->phone_no2}}
										</td>
										<td>
											{{$distributor->reference_id}}
										</td>
										<td>
											{{$distributor->distributor_discount}}
										</td>
										<td>
											{{$distributor->rewards_card_number}}
										</td>
										<td>
											{{$distributor->gst_number}}
										</td>
										<td>
											{{$distributor->pan_number}}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
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
                        <span class="card-label fw-bolder fs-3 mb-1"> Edit Target </span>
                    </h1>

                     <div class="card-toolbar">
                </div>

                </div>

                <div class="card-body py-3">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-7">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Role</th>
                                        <th>Reference Id</th>
                                        <th>Target</th>
                                    </tr>
                                    @php
                                    $c = 1;
                                    @endphp
                                    @foreach($user as $value)
                                        <tr>
                                            <td>{{ $c++ }}</td>
                                            <td>{{ $value->company_name }}</td>
                                            <td>{{ ucwords(str_replace('_', ' ', $value->role)) }}</td>
                                            <td>{{ $value->reference_id }}</td>
                                            <td>
                                                <input type="number" step="any" multiple required class="form-control target_input_fields" value="{{ $value->target_tonnage }}" data-value="{{ $value->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('.target_input_fields').on('change', function(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to edit this target!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, edit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.target.store') }}",
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                value: $(this).val(),
                                target_id: $(this).attr('data-value')
                            },
                            success: function(response) {
                            //    console.log(response.status);
                                if(response.status){
                                    toastr.success(response.msg);
                                }else{
                                    toastr.error('Something want wrong');
                                }
                            },
                        });
                    }
                });
            });
        });
    </script>
@endsection

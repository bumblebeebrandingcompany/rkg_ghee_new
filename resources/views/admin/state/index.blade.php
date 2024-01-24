@extends('layout.app')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Add State</h1>
            </div>
            <div class="card-body">
                <form class="row" id="" method="POST" action="{{ route('admin.states.store') }}">
                    @csrf
                    <div class="col-md-3 mb-10">
                        <label for="state_key" class="required form-label">State</label>
                        <select id="state_key" required class="form-select" name="state_key">
                            <option selected value="">Select state</option>
                                @foreach ($states as $key => $state)
                                    @if (!in_array($state, $add_states))
                                        <option  value="{{ $key }}">{{ $state }}</option>
                                    @endif
                                @endforeach
                        </select>
                    </div>
                    <div class="mt-8 col-md-3">
                        <button type="submit" id="state_sub_btn" class="btn btn-primary ">ADD</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <div class="row mt-10">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <h1 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder fs-3 mb-1">States
                            </span>
                        </h1>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                            {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-rounded table-striped border gy-7 gs-7" id="states_list_table">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
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
       var states_list_table = $('#states_list_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('admin.states.index')}}',
                    },
                  
                    columns: [
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                });
 </script>
@endsection


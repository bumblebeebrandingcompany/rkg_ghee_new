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
            <div class="card-header border-1 pt-5">
                <h1 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">
                        Download Media
                    </span>
                </h1>
                @if(in_array(auth()->user()->role, ['admin']))
                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="" data-bs-original-title="Click to upload media">
                        <a href="{{route('medias.create')}}" class="btn btn-sm btn-light btn-active-primary">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-plus" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z"/>
                                  <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                </svg>
                            </span>
                            Upload Media
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body py-3">
                <div class="row mt-5">
                    @forelse($medias as $media)
                        <div class="col-md-4">
                            <div class="card card-dashed">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        {{$media->name}}
                                    </h3>
                                    @if(in_array(auth()->user()->role, ['admin']))
                                        <div class="card-toolbar">
                                            <a href="{{route('medias.edit', ['media' => $media->id])}}"
                                                type="button" class="btn btn-sm btn-light">
                                                Edit
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <span 
                                        class="d-flex justify-content-center align-items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36%" height="1%" fill="currentColor" class="bi bi-folder2-open" viewBox="0 0 16 16">
                                          <path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14V3.5zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5V6zm-.367 1a.5.5 0 0 0-.496.562l.64 5.124A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-1.314l.64-5.124A.5.5 0 0 0 14.367 7H1.633z"/>
                                        </svg>
                                    </span>
                                    {{$media->description}}
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <span class="mt-2">
                                        {{\Carbon\Carbon::parse($media->created_at)->format('d/m/Y')}}
                                    </span>
                                    <a href="{{route('download.media', ['id' => $media->id])}}" target="_blank"
                                        class="btn btn-sm btn-light-primary">
                                        Download
                                    </a>
                                    @can('delete')
                                        <a data-href="{{route('medias.destroy', ['media' => $media->id])}}"
                                            class="btn btn-sm btn-light-danger delete_media">
                                            Delete
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 mt-5">
                            <div class="alert alert-info d-flex align-items-center p-5 mb-10">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
                                <span class="svg-icon svg-icon-2hx svg-icon-info me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor"></path>
                                        <path d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-info">No media found</h4>
                                    <span>
                                        No medias uploaded yet!
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        //delete media
        $(document).on('click', '.delete_media', function(){
            Swal.fire({
              title: 'Are you sure?',
              text: "You want to delete this media!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                   $.ajax({
                        method: "DELETE",
                        url: $(this).data('href'),
                        dataType: 'json',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            if(response.success){
                                toastr.success(response.msg);
                                setTimeout(() => {
                                    location.reload();
                                }, 250)
                            } else {    
                                toastr.error(response.msg);
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
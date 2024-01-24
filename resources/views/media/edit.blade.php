@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h1 class="">
            Edit Media
        </h1>
    </div>
</div>
<div class="row">
    <div class="card card-dashed">
        <div class="card-header">
            <h1 class="card-title">
                Edit Media
            </h1>
        </div>
        <div class="card-body">
            <form class="row" id="edit_upload_media" method="POST" action="{{route('medias.update', ['media' => $media->id])}}"
                enctype='multipart/form-data'>
                @csrf
                @method('PUT')
                <div class="mb-10 col-md-12">
                    <label for="name" class="required form-label">
                        Name
                    </label>
                    <input type="text" name="name" required class="form-control form-control-solid"
                        id="name" placeholder="Media Name" value="{{$media->name}}" />
                </div>
                <div class="mb-10 col-md-12">
                    <label for="description" class="form-label">
                        Description
                    </label>
                    <textarea class="form-control form-control-solid" rows=5
                        name="description" id="description">{{$media->description}}</textarea>
                </div>
                <div class="mt-10 col-md-12">
                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    $(document).ready(function(){
        $('form#edit_upload_media').validate({
            submitHandler: function(form, e) {
                if ($('form#edit_upload_media').valid()) {
                    form.submit();
                }
            }
        });
    });
</script>
@endsection
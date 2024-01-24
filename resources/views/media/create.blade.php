@extends('layout.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <h1 class="">
            Upload Media
        </h1>
    </div>
</div>
<div class="row">
    <div class="card card-dashed">
        <div class="card-header">
            <h1 class="card-title">
                Upload Media
            </h1>
        </div>
        <div class="card-body">
            <form class="row" id="upload_media" method="POST" action="{{route('medias.save')}}"
                enctype='multipart/form-data'>
                @csrf
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">
                        Name
                    </label>
                    <input type="text" name="name" required class="form-control form-control-solid"
                        id="name" placeholder="Media Name"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="file_name" class="form-label required">
                        File
                    </label>
                    <input type="file" name="file_name" class="form-control form-control-solid" id="file_name"
                        required/>
                </div>
                <div class="mb-10 col-md-12">
                    <label for="description" class="form-label">
                        Description
                    </label>
                    <textarea class="form-control form-control-solid" rows=5
                        name="description" id="description"></textarea>
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
        $('form#upload_media').validate({
            submitHandler: function(form, e) {
                if ($('form#upload_media').valid()) {
                    form.submit();
                }
            }
        });
    });
</script>
@endsection
@extends('layouts.layoutpage')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if(Session::has('message'))
                    <div class="alert alert-success alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('message') }}
                    </div>
                    @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if(Session::has('messagefail'))
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('messagefail') }}
                    </div>
                    @endif
                </div>
            </div>
            
            
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard - Upload Image</div>
                
                <div class="panel-body">
                    <form onsubmit="fileValidation(); return false;" id="myForm" action="/image/store" enctype="multipart/form-data"  method="post">
                       {{ csrf_field() }}
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="image_name">Image Name</label>
                            <input type="text" class="form-control" id="image_name" name="image_name">
                        </div>
                        <div class="form-group">
                            <label for="display_name">Display Name (Less than 80 characters)</label>
                            <input type="text" class="form-control" id="display_name" name="display_name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description (Less than 150 characters)</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="size">Image Size</label>
                            <input type="text" class="form-control" id="size" name="size">
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="sel1">Album</label>
                                <select class="form-control" id="album" name="album">
                                    @foreach($albums as $album)
                                    <option>{{ $album->name }}</option>
                                    @endforeach
                                </select>
                            </div> 
                        </div>
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="size">Uploaded Date</label>
                            <input type="text" class="form-control" id="uploaded_date" name="uploaded_date">
                        </div>
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="size">Current Logged In User</label>
                            <input type="text" class="form-control" id="uploaded_by" name="uploaded_by" value="{{ Auth::user()->id }}">
                        </div>
                        <div class="form-group">
                            <label for="file">Upload Image (Maximum file size {{ App\Setting::all()->first()->max_uploaded_file_size }} KB)</label>
                            <input type="file" id="file" name="file[]">
<!--                            <input type="file" id="file" name="file[]" multiple>  for multiple file upload -->
                        </div>
                        <button id="btnUpload" type="submit" class="btn btn-default">Submit</button>
                        <div id="alert" class="alert alert-success alert-dismissable" hidden="hidden">
                            <strong>Warning!</strong> Only select JPG or JPEG file and files must be less than {{ App\Setting::all()->first()->max_uploaded_file_size }} KB. <br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

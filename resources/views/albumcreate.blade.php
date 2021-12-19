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
                <div class="panel-heading">Dashboard - Create Album</div>

                <div class="panel-body">
                    <form onsubmit="fileValidation(); return false;" id="myForm" action="/album/store" enctype="multipart/form-data"  method="post">
                       {{ csrf_field() }}
                        <div class="form-group">
                            <label for="album_name">Album Name (without any space or any special character within 20 characters)</label>
                            <input type="text" class="form-control" id="album_name" name="album_name">
                        </div>
                        <div class="form-group">
                            <label for="file">Cover Image</label>
                            <input type="file" id="file" name="file">
                        </div>
                        <button id="btnUpload" type="submit" class="btn btn-default">Submit</button>
                        <div id="alert" class="alert alert-success alert-dismissable" hidden="hidden">
                            <strong>Warning!</strong> Only select JPG or JPEG file and files must be less than {{ App\Setting::all()->first()->max_uploaded_file_size }} KB.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
                <div class="panel-heading">Dashboard - Edit Image</div>

                <div class="panel-body">

                    <img class="images img-responsive" src="/upload/images/{{$imgEdit->name}}" alt="{{$imgEdit->name}}">

                    <br>

                    <form id="myForm" action="{{action('PhotoGallary@update',$imgEdit->id)}}" enctype="multipart/form-data" method="post">
                        {{ csrf_field() }}
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="image_name">Image Name</label>
                            <input type="text" class="form-control" id="image_name" name="image_name" value="{{ $imgEdit->name }}">
                        </div>
                        <div class="form-group">
                            <label for="display_name">Display Name</label>
                            <input type="text" class="form-control" id="display_name" name="display_name" value="{{ $imgEdit->display_name }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description" value="{{ $imgEdit->description }}">
                        </div>
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="size">Image Size</label>
                            <input type="text" class="form-control" id="size" name="size" value="{{ $imgEdit->size }}">
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label for="sel1">Album</label>
                                <select class="form-control" id="album" name="album">
                                    <option hidden="hidden" value="{{ $imgEdit->album->name }}" selected>{{ $imgEdit->album->name }}</option>
                                    @foreach($albums as $album)
                                    <option>{{ $album->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="size">Uploaded Date</label>
                            <input type="text" class="form-control" id="uploaded_date" name="uploaded_date" value="{{ $imgEdit->uploaded_date }}">
                        </div>
                        <div class="form-group hidden-lg hidden-md hidden-sm hidden-xs">
                            <label for="size">Current Logged In User</label>
                            <input type="text" class="form-control" id="uploaded_by" name="uploaded_by" value="{{ Auth::user()->id }}">
                            <p>Uploaded by: {{ $imgEdit->uploaded_by }}</p>
                        </div>
                        <button id="btnUpload" type="submit" class="btn btn-default">Update</button>
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
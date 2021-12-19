@extends('layouts.layoutpage')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h4>Edit Profile</h4></div>
                <div class="panel-body">
                    <p>
                        Your User ID is: <b>{{ Auth::user()->id }}</b> </br>
                        (Never try to change your user id.)
                    </p>
                    </br>
                    <form onsubmit="fileValidation(); return false;" id="myForm" action="{{action('PhotoGallary@updateProfile')}}" enctype="multipart/form-data"  method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="display_name">Name (without any space)</label>
                            <input type="text" class="form-control" id="profile_name" name="profile_name" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="form-group">
                            <label for="display_name">Email (verified email)</label>
                            <input type="email" class="form-control" id="profile_email" name="profile_email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="form-group">
                            <label for="file">File input</label>
                            <input type="file" id="file" name="file">
                        </div>
                        <button id="btnUpload" type="submit" class="btn btn-default">Submit</button>
                        <div id="alert" class="alert alert-success alert-dismissable" hidden="hidden">
                            <strong>Warning!</strong> Only select JPG or JPEG file and files must be less than {{ App\Setting::all()->first()->max_uploaded_file_size }} KB.
                        </div>
                    </form>
                    <br>
                    <div class="alert alert-success alert-dismissable fade in">
                        <p>
                            'Name' must be without space. But if you use space, it will not problem. File name of profile image will be created as like user name, so that try to put user name without any space. 'Email' must be verified, because this email will be used to reset your password.
                            During submit, some times a message may be displayed, when you change your user name but don't change any character. For example, you change from 'rezwansaki' to 'RezwanSaki'. 
                        </p>
                    </div>
                    
                    @if(Session::has('message'))
                    <div class="alert alert-danger alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('message') }}
                        <br>
                        <a href="/dashboard">Go Back</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

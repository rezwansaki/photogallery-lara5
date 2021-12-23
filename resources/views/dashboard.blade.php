@extends('layouts.layoutpage')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Dashboard</h2>
                    @if(Session::has('message'))
                    <div class="alert alert-success alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('message') }}
                    </div>
                    @endif

                    @if(Session::has('messagefail'))
                    <div class="alert alert-success alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('messagefail') }}
                    </div>
                    @endif
                </div>


                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <!-- Left Side -->
                    <div class="col-md-4">

                        <ul class="list-group">
                            <li class="list-group-item">
                                <form action="/admin/showSettings" method="get">
                                    <center>
                                        <button style="margin-left:10px;" class="btn btn-primary">
                                            Settings
                                        </button>
                                    </center>
                                </form>
                                <br>
                                <p class="alert alert-danger">If you are the first time in here, please change the settings first. Otherwise, 'Reset Password' system will not work.</p>
                            </li>
                        </ul>


                        <ul class="list-group">
                            <li class="list-group-item">
                                <form action="/admin/deleteallimages" method="post" class="destroyImage">
                                    <input type="hidden" name="_method" value="delete">
                                    {{ csrf_field() }}
                                    <center>
                                        <button style="margin-left:10px;" class="btn btn-danger" onclick="return confirm('Please be careful!! It removes all uploaded images. Are you sure?')">
                                            Delete All Images
                                        </button>
                                    </center>
                                </form>
                                <br>
                                <p class="alert alert-danger">Please be careful! It removes all uploaded images from albums and also delete data from database of them. It doesn't remove user information and albums.</p>
                            </li>
                        </ul>


                        <ul class="list-group">
                            <li class="list-group-item">
                                <form action="/admin/reset" method="post" class="reset">
                                    <input type="hidden" name="_method" value="delete">
                                    {{ csrf_field() }}
                                    <center>
                                        <button style="margin-left:10px;" class="btn btn-danger" onclick="return confirm('Please be careful!! It removes all uploaded images and more. It removes all uploaded images, all albums, data from database and also removes users with their images. It will be a fresh gallary to use new one. Are you sure?')">
                                            Reset
                                        </button>
                                    </center>
                                </form>
                                <br>
                                <p class="alert alert-danger">Please be careful! It removes everything which are created by admin. It removes all uploaded images, all albums, data from database and also removes users with their images. It will be a fresh gallary to use new one.</p>
                            </li>
                        </ul>
                    </div>
                    <!-- End Of Left Side -->

                    <!-- Right Side -->
                    <div class="col-md-8 col-xs-8">
                        <!-- Profile -->
                        <div class="row">
                            <div class="col-md-12 col-xs-12 proTitle">Profile</div>
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-xs-4"></div>
                                    <div class="col-md-4 col-xs-4">
                                        @if(file_exists( public_path().'/upload/users/'.Auth::user()->name.'.jpg' ))
                                        <img class="media-object proPic" src="/upload/users/{{Auth::user()->name}}.jpg" alt="">
                                        @else
                                        <img class="media-object proPic" src="images/imagenotfound.jpg" alt="Image Not Found!">
                                        @endif
                                    </div>
                                    <div class="col-md-4 col-xs-4"></div>
                                </div>
                            </div>
                            <!-- Profile Information -->
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <table class="table table-responsive">
                                            <tr>
                                                <td class="proInfoLeft">ID : </td>
                                                <td class="proInfoRight"> {{ Auth::user()->id }} </td>
                                            </tr>
                                            <tr>
                                                <td class="proInfoLeft">Name : </td>
                                                <td class="proInfoRight"> {{ Auth::user()->name }} </td>
                                            </tr>
                                            <tr>
                                                <td class="proInfoLeft">Email : </td>
                                                <td class="proInfoRight"> {{ Auth::user()->email }} </td>
                                            </tr>
                                        </table>
                                        <hr>
                                        <div class="proBtnEdit">
                                            <a href="/editProfile" class="btn btn-primary">Edit Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Profile Information -->
                        </div>
                        <!-- End of Profile -->
                    </div>
                    <!-- /End of Right Side -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
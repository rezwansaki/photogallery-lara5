@extends('layouts.layoutpage')

@section('content')

<div class="container">
    @foreach($imageImage as $imageImg)
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 singleImage">
            <a href="/upload/images/{{$imageImg->name}}" target="_blank">
                <img class="img-responsive" src="/upload/images/{{$imageImg->name}}" alt="{{$imageImg->name}}">
            </a>
        </div>
    </div>

    <div class="row" id="imageDetailsContainer">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <a href="{{ URL::previous() }}"><span class="glyphicon glyphicon-chevron-left"></span></a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 totalViews">
            Views: {{ $imageImg->image_views }}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="media">
                <div class="media-left">
                    @if(file_exists( public_path().'/upload/users/'.Auth::user()->name.'.jpg' ))
                    <img class="media-object" src="/upload/users/{{App\User::find($imageImg->uploaded_by)->name}}.jpg" alt="{{App\User::find($imageImg->uploaded_by)->name}}.jpg" style="width:60px">
                    @else
                    <img class="media-object" src="/images/imagenotfound.jpg" alt="Image Not Found!" style="width:60px">
                    @endif
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{$imageImg->display_name}}</h4>
                    <div class="user">Uploaded By <a href="#">{{App\User::find($imageImg->uploaded_by)->name}}</a></div>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 totalViews">
            <table class="table table-responsive imageInfo">
                <tr>
                    <th>Image Id:</th>
                    <td>{{$imageImg->id}}</td>
                </tr>
                <tr>
                    <th>Image File Name:</th>
                    <td>{{$imageImg->name}}</td>
                </tr>
                <tr>
                    <th>Display Image Name:</th>
                    <td>{{$imageImg->display_name}}</td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td>{{$imageImg->description}}</td>
                </tr>
                <tr>
                    <th>Image Size:</th>
                    <td>{{ number_format($imageImg->size/1024,2) }} KB</td>
                </tr>
                <tr>
                    <th>Album:</th>
                    <td>{{$imageImg->album->name}}</td>
                </tr>
                <tr>
                    <th>Uploaded Date:</th>
                    <td>{{$imageImg->uploaded_date}}</td>
                </tr>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endsection
@extends('layouts.layoutpage')

@section('content')
<div class="container">
    <div class="row" id="topbar">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Albums
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="/">All</a></li>
                    @foreach($albums as $album)
                    <li><a href="/album/id/{{$album->id}}">{{$album->name}}
                            <span class="badge"> {{$album->images()->count()}} </span></a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
            <form class="form-horizontal" action="{{action('PhotoGallary@search')}}" method="get">
                {{ csrf_field() }}
                <div class="form-group has-success has-feedback">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="search" name="search">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>

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

    <div class="row">
        @foreach($imgs as $img)
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 containter-images">
            <a href="/image/id/{{$img->id}}"><img class="images img-responsive" src="/upload/images/thumb/thumb_{{$img->name}}" alt="{{$img->name}}"></a>

            @if (Auth::check())
            <div class="row middle">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a href="/image/id/{{$img->id}}/edit" class="btn btn-info">Edit</a> <br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <form action="/image/id/{{$img->id}}/destroy" method="post" class="destroyImage">
                        <input type="hidden" name="_method" value="delete">
                        {{ csrf_field() }}
                        <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {{ $imgs->links() }}
        </div>
    </div>
    <!-- Pagination -->
</div>
@endsection
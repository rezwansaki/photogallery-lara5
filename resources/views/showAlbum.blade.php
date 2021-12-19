@extends('layouts.layoutpage')

@section('content')
<div class="container">
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
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 containter"> 
            <div class="panel panel-default">
                <div class="panel-body albumPanel">
                    <a href="/album/id/{{$img->id}}"><img class="thumbnail img-responsive" src="/upload/albums/{{$img->cover_image}}" alt="{{$img->cover_image}}"></a>
                </div>
                <div class="panel-footer">
                    <center><b>{{$img->name}}</b> <span class="badge"> {{$img->images()->count()}} </span></center>
                </div>
            </div>
            <div class="middle">
               @if (Auth::check())
               <div class="row">
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                       <a href="/album/edit/{{$img->id}}" class="btn btn-info">Edit</a> <br>
                   </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                       <form action="/album/id/{{$img->id}}/destroy" method="post" class="destroyImage">
                           <input type="hidden" name="_method" value="delete">
                           {{ csrf_field() }}
                           <button class="btn btn-danger" onclick="return confirm('Are you sure, you want to delete this album? If this album is empty, you can delete this, otherwise you can not delete this album.')">Delete</button>
                       </form>     
                   </div>
               </div>
                @endif
            </div>
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

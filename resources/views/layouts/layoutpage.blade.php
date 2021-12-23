<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <link href="{{ asset('css/css/bootstrap.min.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('css/mystyle.css') }}" rel='stylesheet' type='text/css' /> <!-- My own style code -->

    <!-- Favicon -->
    <link rel="shortcut icon" href="images\favicon.ico" />
</head>

<body>

    <div class="container-fluid" id="maincontainer">
        <!-- Navbar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"> {{config('app.name')}} </a>
                </div>
                <div class="collapse navbar-collapse" id="main_menu">
                    <!--<ul class="nav navbar-nav navbar-right">
                       @if(Auth::check())
                        <li><a href="/login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                       @endif
                    </ul>-->
                    <ul class="nav navbar-nav navbar-right">
                        @if(Auth::check())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/dashboard">
                                        <span class="glyphicon glyphicon-dashboard"></span>
                                        Dashboard</a></li>
                                <li><a href="/image/create">
                                        <span class="glyphicon glyphicon-cloud-upload"></span>
                                        Upload Image</a></li>
                                <li><a href="/album/showAlbum">
                                        <span class="glyphicon glyphicon-th-large"></span>
                                        Album</a></li>
                                <li><a href="/album/create">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        Create Album</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <span class="glyphicon glyphicon-log-out"></span>
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li><a href="{{ route('login') }}">
                                <span class="glyphicon glyphicon-log-in"></span>
                                Login</a></li>

                        @if(App\User::all()->isEmpty())
                        <li>
                            <a href="{{ route('register') }}">
                                <span class="glyphicon glyphicon-asterisk"></span>
                                Register</a>
                        </li>
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        </nav>
        <!-- /Navbar -->
    </div>



    <div class="container-fluid" id="maincontainer">
        @yield('content')
    </div>





    <div class="container-fluid" id="footer">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p>Copyright &copy;Photo Gallary 2018 | Developed by Alin | Practice work | Laravel 5.5</p>
            </div>
        </div>
    </div>


    <!-- js files -->
    <script src="{{ asset('js/js/jquery.min.js') }}"> </script>
    <script src="{{ asset('js/js/bootstrap.min.js') }}"> </script>
    <!-- /js files -->

</body>

</html>
@if(url()->current() == route('front.home'))
    <header id="header-inverse">
        <nav class="navbar navbar-default navbar-fixed-top menu">
            <div class="container">

                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index-register.html"><img src="/front/images/logo.png"
                                                                            alt="logo"/></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right main-menu">
                        <li class="dropdown"><a href="{{route('front.home')}}">Home</a></li>
                        <li class="dropdown"><a href="{{route('front.feeds.index')}}">Feeds</a></li>
                    </ul>
                    <form class="navbar-form navbar-right hidden-sm ">
                        <div class="form-group">
                            <i class="icon ion-android-search"></i>
                            <input type="text" class="form-control" placeholder="Search friends, photos, videos">
                        </div>
                    </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container -->
        </nav>
    </header>
@else
    <header id="header">
        <nav class="navbar navbar-default navbar-fixed-top  menu">
            <div class="container">

                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index-register.html">
                        <img src="/front/images/logo.png" alt="logo"/>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right main-menu">
                        <li class="dropdown"><a href="{{route('front.home')}}">Home</a></li>
                        <li class="dropdown"><a href="{{route('front.feeds.index')}}">Feeds</a></li>
                    </ul>
                    <form class="navbar-form navbar-right hidden-sm ">
                        <div class="form-group">
                            <i class="icon ion-android-search"></i>
                            <input type="text" class="form-control" placeholder="Search friends, photos, videos">
                        </div>
                    </form>
                </div>

                @auth
                <div class="header-right">
                    <ul class="headermenu" id="notifications_container">
                        @include('front.layouts.notifications')
                    </ul>
                </div>
                @endauth
                <!-- /.navbar-collapse -->
            </div><!-- /.container -->
        </nav>
    </header>
@endif

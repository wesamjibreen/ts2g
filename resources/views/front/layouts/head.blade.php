<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="This is social network html5 template available in themeforest......" />
    <meta name="keywords" content="Social Network, Social Media, Make Friends, Newsfeed, Profile Page" />
    <meta name="robots" content="index, follow" />
    <title>Technical Solutions To Globe | @yield('sub_title')</title>
    {!! HTML::style(front_url("css/bootstrap.min.css")) !!}
    {!! HTML::style(front_url("css/style.css")) !!}
    {!! HTML::style(front_url("css/ionicons.min.css")) !!}
    {!! HTML::style(front_url("css/font-awesome.min.css")) !!}
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i" rel="stylesheet">
    @stack('front_css')
    <link rel="shortcut icon" type="image/png" href="/front/images/fav.png"/>
    <meta name="csrf-token" content="{{csrf_token()}}">
    {{--window.Laravel = {!! json_encode(['csrfToken'=> csrf_token()]) !!}--}}
</head>

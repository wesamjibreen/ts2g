<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{@$description}}" />
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

<meta name="Keywords" content="Programming,IT,Design" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Description" content="{{@$description}}"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="twitter:title" content="Technical Solutions To Globe | @yield('sub_title')">
<meta name="twitter:description" content="{{@$description}}">
<meta name="twitter:image:src" content="{{@$image}}">
<meta property="og:type" content="article" />
<meta name="twitter:image" content="{{@$image}}">
<meta  property="og:title"  content="{{@$title}}" />
<meta property="og:description"  content="{{ @$description}}" />
<meta property="og:image"  content="{{ @$image}}" />
<meta id="url_property" property="og:url" content="{{isset($url) ? $url : url()->current()}}" />
<meta name="twitter:card" content="summary" />
</head>

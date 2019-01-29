<!DOCTYPE html>
<html lang="en">
@include('front.layouts.head')
<body>
@include('front.layouts.header')

@yield('content')

{{--<div id="spinner-wrapper">--}}
    {{--<div class="spinner"></div>--}}
{{--</div>--}}

@include('front.layouts.js')

</body>

</html>

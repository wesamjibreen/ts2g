@extends('front.layouts.index')
@section('sub_title','Feeds')
@section('content')
    @push('front_css')

    @endpush
    <div class="container" style="background-color: white;">
        <div class="timeline"  style="background-color: white;">
            <div class="timeline-cover">
                <div class="timeline-nav-bar hidden-sm hidden-xs">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="profile-info">
                                <img src="{{$user->getUserAvatar('180x180')}}" alt=""
                                     class="img-responsive profile-photo"/>
                                <h3>{{$user->name}}</h3>
                                @if($user->id == user()->id)
                                    <a class="btn btn-info" href="{{route('front.profile.edit.index')}}">Edit Profile</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
                            <ul class="list-inline profile-menu">
                                <li><a href="{{route('front.profile.show',$user->id)}}" >Timeline</a></li>
                                <li><a href="{{route('front.profile.following',$user->id)}}">Following</a></li>
                                <li><a href="{{route('front.profile.followers',$user->id)}}">Followers</a></li>
                            </ul>
                            <ul class="follow-me list-inline">
                                <li>{{$user->followersCount()}} followers</li>
                                @if(user()->id != @$user->id && !isset(user()->following()->find($user->id)->id ))
                                    <li>
                                        <div>
                                            <button class="btn-primary follow-user-ev" href="javascript:;"
                                                    data-url="{{route('front.profile.follow',[$user->id])}}">Follow
                                            </button>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="navbar-mobile hidden-lg hidden-md">
                    <div class="profile-info">
                        <img src="images/users/user-1.jpg" alt="" class="img-responsive profile-photo"/>
                        <h4>{{$user->name}}</h4>
                    </div>
                    <div class="mobile-menu">
                        <ul class="list-inline">
                            <li><a href="timline.html" class="active">Timeline</a></li>
                            <li><a href="timeline-about.html">About</a></li>
                            <li><a href="timeline-album.html">Album</a></li>
                            <li><a href="timeline-friends.html">Friends</a></li>
                        </ul>
                        <button class="btn-primary">Add Friend</button>
                    </div>
                </div><!--Timeline Menu for Small Screens End-->

            </div>
            <div id="page-contents">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-7">
                        <div id="app">
                            <feed v-bind:data="{{
                        json_encode([
                        'id'=>user()->id,
                        'is_my_profile'=>user()->id != @$user->id,
                        'username'=>user()->name,
                        'user_id'=> $user->id,
                        'posts_type'=>'my',
                        'link'=>route('front.profile.show',user()->id),
                        'avatar'=>user()->getUserAvatar('80x80')])
                        }}"></feed>
                        </div>
                    </div>
                    @if(isset($peopleToFollow) && $peopleToFollow->count() >0 )
                        <div class="col-md-2 static">
                            <div class="suggestions" id="sticky-sidebar">
                                <h4 class="grey">Who to Follow</h4>
                                @foreach($peopleToFollow as $userToFollow)
                                    <div class="follow-user">
                                        <img src="{{$userToFollow->getUserAvatar('70x70')}}" alt=""
                                             class="profile-photo-sm pull-left"/>
                                        <div>
                                            <h5>
                                                <a href="{{route('front.profile.show',[$userToFollow->id])}}">{{$userToFollow->getUserName()}}</a>
                                            </h5>
                                            <a href="javascript:;"
                                               data-url="{{route('front.profile.follow',[$userToFollow->id])}}"
                                               class="text-green follow-user-ev">Follow</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('front.layouts.footer')
    {!! HTML::script(asset('/js/app.js')) !!}
    @push('front_js')
        <script>
            $(document).on('click', '.follow-user-ev', function () {
                var _this = $(this);
                $.ajax({
                    url: _this.data('url'),
                    method: 'PATCH',
                    success: function (response) {
                        if (response.status) {
                            _this.parent().parent().fadeOut();
                            $.notify(response.message, {
                                type: 'success',
                                allow_dismiss: true,
                                animate: {
                                    enter: 'animated bounceIn',
                                    exit: 'animated bounceOut'
                                }
                            });
                        } else {
                            $.notify(response.message, {
                                type: 'warning',
                                allow_dismiss: true,
                                animate: {
                                    enter: 'animated bounceIn',
                                    exit: 'animated bounceOut'
                                }
                            });
                        }
                    },
                    error: function (jqXhr) {
                        $.notify('Check Your Internet Connection', {
                            type: 'warning',
                            allow_dismiss: true,
                            animate: {
                                enter: 'animated bounceIn',
                                exit: 'animated bounceOut'
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@stop
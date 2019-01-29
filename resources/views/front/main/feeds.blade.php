@extends('front.layouts.index')
@section('sub_title','Feeds')

@section('content')
    @push('front_css')

    @endpush


    <div id="page-contents" style="background-color: white">
        <div class="container">
            <div class="row">

                <!-- Newsfeed Common Side Bar Left
                ================================================= -->
                <div class="col-md-3 static">
                    <div class="profile-card">
                        <img src="{{user()->getUserAvatar('70x70')}}" alt="user" class="profile-photo"/>
                        <h5><a href="{{route('front.profile.show',[user()->id])}}" class="text-white"> {{user()->getUserName()}}</a></h5>
                        <a href="#" class="text-white"><i class="ion ion-android-person-add"></i> {{user()->followersCount()}} followers</a>
                    </div><!--profile card ends-->
                    <ul class="nav-news-feed">
                        <li><i class="icon ion-ios-paper"></i>
                            <div><a href="newsfeed.html">My Newsfeed</a></div>
                        </li>
                        <li><i class="icon ion-ios-people"></i>
                            <div><a href="newsfeed-people-nearby.html">People Nearby</a></div>
                        </li>
                        <li><i class="icon ion-ios-people-outline"></i>
                            <div><a href="newsfeed-friends.html">Friends</a></div>
                        </li>
                        <li><i class="icon ion-chatboxes"></i>
                            <div><a href="newsfeed-messages.html">Messages</a></div>
                        </li>
                        <li><i class="icon ion-images"></i>
                            <div><a href="newsfeed-images.html">Images</a></div>
                        </li>
                        <li><i class="icon ion-ios-videocam"></i>
                            <div><a href="newsfeed-videos.html">Videos</a></div>
                        </li>
                    </ul><!--news-feed links ends-->
                    <div id="chat-block">
                        <div class="title">Chat online</div>
                        <ul class="online-users list-inline">
                            <li><a href="newsfeed-messages.html" title="Linda Lohan"><img
                                            src="/front/images/users/user-2.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="Sophia Lee"><img
                                            src="/front/images/users/user-3.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="John Doe"><img
                                            src="/front/images/users/user-4.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="Alexis Clark"><img
                                            src="/front/images/users/user-5.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="James Carter"><img
                                            src="/front/images/users/user-6.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="Robert Cook"><img
                                            src="/front/images/users/user-7.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="Richard Bell"><img
                                            src="/front/images/users/user-8.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="Anna Young"><img
                                            src="/front/images/users/user-9.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                            <li><a href="newsfeed-messages.html" title="Julia Cox"><img
                                            src="/front/images/users/user-10.jpg" alt="user"
                                            class="img-responsive profile-photo"/><span class="online-dot"></span></a>
                            </li>
                        </ul>
                    </div><!--chat block ends-->
                </div>
                <div class="col-md-7">

                    <div id="app">
                        <feed v-bind:data="{{
                        json_encode([
                        'id'=>user()->id,
                        'username'=>user()->name,
                        'user_id'=> 0,
                        'posts_type'=>'feeds',
                        'link'=>route('front.profile.show',user()->id),
                        'avatar'=>user()->getUserAvatar('80x80')])
                        }}">
                        </feed>
                    </div>
                    <!-- Post Create Box
                    ================================================= -->

                </div>

                <!-- Newsfeed Common Side Bar Right
                ================================================= -->
                @if(isset($peopleToFollow) && $peopleToFollow->count() >0 )
                    <div class="col-md-2 static">
                        <div class="suggestions" id="sticky-sidebar">
                            <h4 class="grey">Who to Follow</h4>
                            @foreach($peopleToFollow as $userToFollow)
                                <div class="follow-user">
                                    <img src="{{$userToFollow->getUserAvatar('70x70')}}" alt="" class="profile-photo-sm pull-left"/>
                                    <div>
                                        <h5><a href="{{route('front.profile.show',[$userToFollow->id])}}">{{$userToFollow->getUserName()}}</a></h5>
                                        <a href="javascript:;" data-url="{{route('front.profile.follow',[$userToFollow->id])}}" class="text-green follow-user-ev">Follow</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
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
                            $.notify( response.message, {
                                type : 'success',
                                allow_dismiss: true,
                                animate: {
                                    enter: 'animated bounceIn',
                                    exit: 'animated bounceOut'
                                }
                            });
                        } else {
                            $.notify( response.message, {
                                type : 'warning',
                                allow_dismiss: true,
                                animate: {
                                    enter: 'animated bounceIn',
                                    exit: 'animated bounceOut'
                                }
                            });
                        }
                    },
                    error: function (jqXhr) {
                        $.notify(  'Check Your Internet Connection', {
                            type : 'warning',
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
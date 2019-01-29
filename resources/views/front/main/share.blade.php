@extends('front.layouts.index',['title'=>string_limit($post->body,100),
'description'=>string_limit($post->body,100),
'image'=>$post->images()->count() > 0 ? image_url($post->images()->first()->file_name):$post->getUserAvatar()])
@section('sub_title',string_limit($post->body,100))
@section('content')
    @push('front_css')

    @endpush

    <div id="page-contents" style="background-color: white">
        <div class="container">
            <div class="row">
                <div class="col-md-3 static">

                </div>
                <div class="col-md-7">
                    <div class="post-container">
                        <img src="{{$post->getUserAvatar('90x90')}}" alt="user" class="profile-photo-md pull-left"/>
                        <div class="post-detail">
                            <div class="user-info">
                                <h5><a href="{{route('front.profile.show',$post->user->id)}}" class="profile-link">{{$post->username}}</a>
                                    {{--<span class="following">following</span>--}}
                                </h5>
                                <p class="text-muted">Published {{diff_for_humans($post->created_at)}} </p>
                            </div>

                            <div class="line-divider"></div>
                            <div class="post-text">
                                <p>
                                    {{$post->body}}
                                    <i class="em em-anguished"></i>
                                    <i class="em em-anguished"></i>
                                    <i class="em em-anguished"></i>
                                </p>
                            </div>
                            @if($post->hasShared())
                                @php
                                    $shared_post = $post->originalPost;
                                @endphp
                                <div  class="col-md-12" style="border-radius: 25px;background-color: white;">
                                    <div class="post-container">
                                        <img :src="post.shared_post.profile_pic" alt="user" class="profile-photo-md pull-left"/>
                                        <div class="post-detail">
                                            <div class="user-info">
                                                <h5><a :href="post.shared_post.user_link" class="profile-link">{{$shared_post->getUserName()}}</a>
                                                </h5>
                                                <p class="text-muted">Published {{diff_for_humans($shared_post->created_at)}} </p>
                                            </div>
                                            <div class="line-divider"></div>
                                            <div class="post-text">
                                                <p>
                                                    {{$shared_post->body}}
                                                    <i class="em em-anguished"></i>
                                                    <i class="em em-anguished"></i>
                                                    <i class="em em-anguished"></i>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="line-divider"></div>
                            @foreach($post->comments as $comment)
                                <div  class="post-comment">
                                    <img src="{{$comment->getUserAvatar('80x80')}}" alt="" class="profile-photo-sm"/>
                                    <p>
                                        <a :href="comment.user_link" class="profile-link">{{$comment->getUserName()}} </a>
                                        <i class="em em-laughing"></i>
                                        {{$comment->body}}
                                    </p>
                                    {{--                            <!--<p>{{comment.created_at}}</p>-->--}}
                                </div>
                            @endforeach

                        </div>
                    </div>


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
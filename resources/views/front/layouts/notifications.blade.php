<li>
    <div class="btn-group">
        <button class="btn btn-default dropdown-toggle tp-icon" data-toggle="dropdown">
            <i class="glyphicon glyphicon-globe"></i>
            @if($unReadNotifications > 0)
                <span class="badge"> {{$unReadNotifications}} </span>
            @endif
        </button>
        <div class="dropdown-menu dropdown-menu-head pull-right">
            {{--<h5 class="title text-center">You Have 5 New Notifications</h5>--}}
            <ul class="dropdown-list gen-list">
                @if($allNotifications->count() >0)
                    @foreach($allNotifications as $notification)
                        <li class="new">
                            <a href="javascript">
                            <span class="thumb">
                <img style="border-radius: 25px" src="{{$notification->getAvatar()}}" alt="">
            </span>
                                <span class="desc">
                <span class="name">{{$notification->text}}</span>
                <span class="msg"> {{diff_for_humans($notification->created_at)}} </span>
            </span>
                            </a>
                        </li>
                    @endforeach
                    @else
                    <li> <a href="javascript:;"> There is No Notifications</a></li>
                @endif
            </ul>

        </div>
    </div>
</li>

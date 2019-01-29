<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Notifications\UserNotification;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class FeedsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('shareIndex');
    }

    public function index()
    {
        $data['peopleToFollow'] = User::notFollowing()->where('id', '!=', user()->id)->get();
        return view('front.main.feeds', $data);
    }

    public function getPosts($type, $id)
    {
        if ($type == 'feeds') {
            $posts = Post::whereIn('user_id', array_merge(user()->following()->get()->pluck('id')->toArray(), [user()->id]))->latest()->paginate(20);
        } else {
            $posts = User::findOrFail($id)->posts()->latest()->paginate(20);
        }
        return response()->json(array_merge(['status' => true, 'data' => PostResource::collection($posts)], array_except($posts->toArray(), ['data'])));
    }

    public function postComment(Request $request)
    {
        $post = Post::find($request->id);
        if (isset($post)) {
            $comment = $post->createComment($request);
            $post->user->notify(new UserNotification(['notification_text_id' => 4, 'user' => user()->id, 'post' => $post->id]));
            return response()->json(['status' => true, 'message' => 'Added', 'item' => $comment]);
        }
        return response()->json(['status' => false, 'message' => 'failed']);
    }

    public function publishPost(Request $request)
    {
        $post = user()->createPost($request);
        $post->storeImages($request);
        $post->storeVideos($request);
        return response()->json(['status' => true, 'message' => 'success', 'item' => new PostResource($post)]);
    }

    public function postLike($id)
    {
        $post = Post::find($id);
        if (isset($post)) {
            $post->hasLike(user()->id) ? $post->likes()->detach(user()->id) : $post->likes()->attach(user()->id);
            if ($post->hasLike(user()->id) && $post->user_id != user()->id) {
                $post->user->notify(new UserNotification(['notification_text_id' => 2, 'user' => user()->id, 'post' => $post->id]));
            }
            return response()->json(['status' => true, 'message' => 'success', 'likes' => $post->likes()->select(['id', 'f_name', 'l_name'])->get()]);
        }
        return response()->json(['status' => false, 'message' => 'failed']);
    }

    public function postShare($id, Request $request)
    {
        $sharedPost = Post::find($id);
        if (isset($sharedPost)) {
            $newPost = $sharedPost->share($request);
            $sharedPost->user->notify(new UserNotification(['notification_text_id' => 3, 'user' => user()->id, 'post' => $sharedPost->id]));
            return response()->json(['status' => true, 'message' => 'success', 'item' => new PostResource($newPost), 'shares_count' => $sharedPost->sharesCount()]);
        }
        return response()->json(['status' => false, 'message' => 'failed']);
    }

    public function shareIndex($id)
    {
        $post = Post::find($id);
        return isset($post) ? view('front.main.share',['post'=>$post]) : redirect()->route('front.home');
    }

}

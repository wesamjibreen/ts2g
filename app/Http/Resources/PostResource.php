<?php

namespace App\Http\Resources;

use App\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'username' => $this->getUserName(),
            'user_link' => route('front.profile.show',$this->user->id),
            'profile_pic' => (string)  $this->getUserAvatar('70x70'),
            'body' => (string)  $this->body,
            'comments'=> $this->comments,
            'images'=>$this->images()->get(),
            'videos'=>$this->videos()->get(),
            'shared_post' => $this->hasShared() ? new self($this->originalPost) : null,
            'has_share' => (boolean) $this->hasShared(),
            'share_link' => (string) 'https://facebook.com/sharer/sharer.php?u='.route('front.feeds.posts.index',[$this->id]),
            'shares_count'=>$this->sharesCount(),
            'likes'=> $this->likes()->select(['id','f_name','l_name'])->get(),
            'media'=>$this->media,
            'diff_created_at' =>(string) $this->created_at->diffForHumans(),
            'created_at' => (string)  $this->created_at,
        ];
    }
}

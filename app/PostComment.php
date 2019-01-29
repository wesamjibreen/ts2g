<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{

    protected $guarded = [];

    protected $appends = ['profile_pic', 'user_link', 'username'];

    public function getProfilePicAttribute()
    {
        return $this->getUserAvatar('40x40');
    }

    public function getUserLinkAttribute()
    {
        return route('front.profile.show',$this->user->id);
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(['name' => '']);
    }

    public function getUserAvatar($size = null)
    {
        return $this->user->getUserAvatar($size);
    }

    public function getUserName()
    {
        return $this->user->name;
    }


}

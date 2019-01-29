<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const FILLABLE = [
        'f_name', 'l_name', 'email', 'email_verified_at', 'gender_id', 'birth_date', 'country',
        'city', 'description', 'password', 'profile_image_id', 'cover_image_id',
    ];
    const REGISTER = [
        'f_name', 'l_name', 'email', 'gender_id', 'birth_date', 'country', 'city', 'password'
    ];
    protected $fillable = self::FILLABLE;
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->getUserName();
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * @param array $data
     * @return User
     */
    public static function create(array $data)
    {
        $instance = new self;
        $instance->fill($data);
        $instance->password = bcrypt($data['password']);
        $instance->save();
        return $instance;
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeNotFollowing($q)
    {
        return $q->whereNotIn('id', user()->following()->get()->pluck('id')->toArray());
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profileImage()
    {
        return $this->belongsTo(Image::class, 'profile_image_id', 'id')->withDefault();
    }

    /**
     * @param $loginObject
     * @return mixed
     */
    public function createFacebookLogin($loginObject)
    {
        return UserLogin::create([
            'user_id' => $this->id,
            'type' => 'facebook',
            'external_user_id' => $loginObject->id,
            'name' => $loginObject->name,
            'email' => $loginObject->email,
            'expiresIn' => $loginObject->expiresIn
        ]);
    }

    /**
     * @param $q
     * @param $email
     * @return mixed
     */
    public function scopeSearchByEmail($q, $email)
    {
        return $q->where('email', $email);
    }

    /**
     * @param $path
     * @return bool
     */
    public function saveFacebookAvatar($path)
    {
        $filename = 'image_' . time() . mt_rand() . "_avatar.jpg";
        $newName = ('\uploads\images\\' . $filename);
        $img = \Intervention\Image\Facades\Image::make($path)->stream('jpg', 90);
        Storage::put($newName, $img);
        $image = Image::create([
            'display_name' => $filename,
            'file_name' => $filename,
            'mime_type' => 'jpg',
            'size' => 0
        ]);
        return $this->update(['profile_image_id' => $image->id]);
    }

    /**
     * @param null $size
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getUserAvatar($size = null)
    {
        return image_url($this->profileImage->file_name, $size);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'following_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'following_id', 'follower_id', 'id');
    }

    /**
     * @return int
     */
    public function followersCount()
    {
        return $this->followers()->count();
    }

    /**
     * @return int
     */
    public function followingCount()
    {
        return $this->following()->count();
    }

    public function createPost(Request $request)
    {
        return Post::create([
            'user_id' => auth()->user()->id,
            'body' => $request->body,
            'shared_post_id' => $request->shared_post_id,
        ]);
    }

    public function posts()
    {
        return $this->hasMany(Post::class,'user_id','id');
    }

    public function allNotifications()
    {
        return $this->hasMany(Notification::class,'notifiable_id','id')->orderBy('created_at','DESC');
    }


}

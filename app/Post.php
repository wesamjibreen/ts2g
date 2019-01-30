<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'body', 'shared_post_id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault(['name' => '']);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id', 'id');
    }

    public function getUserAvatar($size = null)
    {
        return $this->user->getUserAvatar($size);
    }

    public function getUserName()
    {
        return $this->user->name;
    }

    public function createComment(Request $request)
    {
        return PostComment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $this->id,
            'body' => $request->body,
        ]);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_likes', 'post_id', 'user_id', 'id');
    }

    public function hasLike($id)
    {
        return $this->likes()->where('id', $id)->count() > 0;
    }

    public function originalPost()
    {
        return $this->belongsTo(self::class, 'shared_post_id', 'id');
    }

    public function hasShared()
    {
        return isset($this->originalPost);
    }

    public function sharesCount()
    {
        return self::where('shared_post_id', $this->id)->count();
    }

    public function share(Request $request)
    {
        return self::create(['body' => $request->body, 'shared_post_id' => $this->id, 'user_id' => user()->id]);
    }

    public function storeImages(Request $request)
    {
        $images = $request->images;
        if (is_array($images) && count($images) > 0) {
            foreach ($images as $i=>$image) {
                $path = '\uploads\images\\';
                $file_name = 'image_' .$i. time() . '.png';
                $file_data = $image['path'];
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data) = explode(',', $file_data);
                if ($file_data != "") {
                    Storage::disk('local')->put($path . $file_name, base64_decode($file_data));
                    $image = Image::create([
                        'display_name' => $image['name'],
                        'file_name' => $file_name,
                        'mime_type' => 'jpg',
                        'size' => 0
                    ]);
                    $media = PostMedia::create([
                        'post_id' => $this->id,
                        'ownerable_id' => $image->id,
                        'ownerable_type' => Image::class,
                        'mime_type' => 'png'
                    ]);
                }
            }
        }
        return true;
    }

    public function storeVideos(Request $request)
    {
        $videos = $request->videos;
        foreach ($videos as $video){
            PostMedia::create([
                'post_id' => $this->id,
                'ownerable_id' => @$video['id'],
                'ownerable_type' => Video::class,
                'mime_type' => @$video['mime_type']
            ]);
        }
        return true;
    }

    public function images()
    {
        $ids = PostMedia::where('ownerable_type', Image::class)->where('post_id', $this->id)->pluck('ownerable_id');
        return Image::whereIn('id', $ids);
    }

    public function videos()
    {
        $ids = PostMedia::where('ownerable_type', Video::class)->where('post_id', $this->id)->pluck('ownerable_id');
        return Video::whereIn('id', $ids);
    }

}

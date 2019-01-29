<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $appends = [
//        'link',
        'is_image'];

//    public function getLinkAttribute()
//    {
//        return $this->isImage() ? image_url();
//    }
//
//
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function ownerable()
    {
        return $this->morphTo();
    }

    public function getIsImageAttribute()
    {
        return $this->isImage();
    }

    public function isImage()
    {
        return (boolean) $this->ownerable_type == Image::class;
    }
}

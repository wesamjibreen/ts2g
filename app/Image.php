<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $attributes = ['file_name' => 'default.png', 'display_name' => '', 'size' => 0];

    protected $fillable = [
        'display_name', 'file_name', 'mime_type', 'size'
    ];

    protected $appends=['link'];

    public static $rules = [
        'image' => 'required|mimes:png,gif,jpeg,jpg,bmp,svg,ico'
    ];

    public static $messages = [
        'image.mimes' => 'The file you are trying to upload has an unsupported formula',
        'image.required' => 'Image Required'
    ];

    public function getLinkAttribute()
    {
        return image_url($this->file_name);
    }
}

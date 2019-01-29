<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $guarded =[];

    public static $rules = [
        'video' => 'required|mimes:avi,wmv,mp4,mov,mpg,flv,mpeg',
    ];

    public static $messages = [
        'image.mimes' => 'الملف الذي تحاول رفعه له صيغة غير مدعومة',
        'image.required' => 'الفيديو مطلوب'
    ];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $appends = ['text'];

    public function getTextAttribute()
    {
        return $this->hasNotificationText() ? $this->strReplace($this->notificationText->text) : '';
    }


    public function strReplace($str)
    {
        $data = json_decode($this->data);
        foreach ($data as $i => $value) {
            switch ($i) {
                case 'user' :
                    $item = User::find($value);
                    $str = str_replace(("#{" . $i . "}#"), $item->name, $str);
                    break;
                case 'post' :
                    $item = Post::find($value);
                    $str = str_replace(("#{" . $i . "}#"), string_limit($item->body, 20), $str);
                    break;
            }
        }
        return $str;
    }

    public function notificationText()
    {
        return $this->belongsTo(NotificationText::class, 'notification_text_id', 'id')->withDefault();
    }

    public function hasNotificationText()
    {
        return isset($this->notificationText);
    }

    public function getAvatar()
    {
        $data = json_decode($this->data);
        foreach ($data as $i => $value) {
            switch ($i) {
                case 'user' :
                    $item = User::find($value);
                    $image = $item->getUserAvatar('100x100');
                    break;
            }
        }
        return $image;
    }
}

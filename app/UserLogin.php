<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLogin extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'type', 'external_user_id', 'name', 'email', 'expiresIn'];

    public static function create(array $data)
    {
        $instance = new self;
        $instance->fill($data);
        $instance->user_id = auth()->user()->id;
        $instance->type = auth()->user()->id;
        $instance->save();
        return $instance;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function hasUser()
    {
        return isset($this->user);
    }
}

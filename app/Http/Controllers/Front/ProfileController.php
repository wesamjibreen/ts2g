<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\UpdateProfileRequest;
use App\Notifications\UserNotification;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function follow($id)
    {
        $following = User::find($id);
        if (isset($following)) {
            if (user()->following()->find($id)) {
                return response()->json(['status' => false, 'message' => 'You\'ve already followed this user.']);
            }
            user()->following()->attach($id);
            $following->notify(new UserNotification(['notification_text_id' => 1, 'user' => user()->id]));
            return response()->json(['status' => true, 'message' => 'You are now following ' . $following->getUserName()]);
        }
        return response()->json(['status' => false, 'message' => 'User Not Found']);
    }

    public function show($id)
    {
        $data['user'] = User::find($id);
        $data['peopleToFollow'] = User::notFollowing()->where('id', '!=', user()->id)->get();
        return isset($data['user']) ? view('front.profile.show', $data) : redirect()->route('front.feeds.index');
    }

    public function editIndex()
    {
        return view('front.profile.edit', ['user' => user()]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $request->request->add(['birth_date' => createDate($request->year, $request->month, $request->day)]);
        user()->update($request->only(User::FILLABLE));
        return response()->json(['status'=>true,'message'=>'Info Edited Successfully']);
    }
}

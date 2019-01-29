<?php

namespace App\Http\Controllers;

use App\Logic\VideoRepository;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class VideoController extends Controller
{
    protected $video;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->video = $videoRepository;
    }

    public function uploadVideo()
    {
        $video = Input::all();
        $response = $this->video->upload($video, 'video');
        return $response;
    }

    public function getVideo($id)
    {
        $path = storage_path('app/uploads/videos/'.$id);
        if(!File::exists($path)) abort(404);
        return response()->file($path);
    }

    public function deleteVideo(Request $request)
    {
        $filename = $request->id;
        if (!$filename) {
            return 0;
        }
        $response = $this->video->delete($filename);

        return $response;
    }


}

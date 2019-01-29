<?php

namespace App\Logic;

use App\Video;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use RobbieP\CloudConvertLaravel\Facades\CloudConvert;
use \Storage;

use Illuminate\Http\Request;

class VideoRepository
{
    private $full_save_path = 'app/uploads/videos/';
    private $save_path = 'uploads/videos/';

    public function upload($form_data, $type)
    {
        $validator = Validator::make($form_data, Video::$rules, Video::$messages);
        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->messages()->first(),
            ], 400);
        }

        $video = $form_data['video'];
        $extension = $video->getClientOriginalExtension();
        $filename = 'video_' . time() . mt_rand();
        $video_size = $video->getClientSize();
        $allowed_filename = $this->createUniqueFilename($filename, $extension);
        $uploadSuccess1 = $this->original($video, $allowed_filename);
        $originalName = str_replace('.' . $extension, '', $video->getClientOriginalName());
        if (!$uploadSuccess1) {
            return Response::json([
                'status' => false,
                'message' => 'حدث خطأ أثناء المعالجة من الخادم',
            ], 500);
        }
        $video = new Video;
        $video->display_name = $originalName . '.' . $extension;
        $video->size = $video_size;
        $video->mime_type = $extension;
        $video->file_name = $allowed_filename;
        $video->save();
        return Response::json([
            'status' => true,
            'file_name' => $allowed_filename,
            'display_name' => $originalName.'.'.$extension,
            'size' =>  $video->size,
            'id' => $video->id
        ], 200);
    }

    public function createUniqueFilename($filename, $extension)
    {
        $path = storage_path($this->full_save_path . $filename . '.' . $extension);
        if (File::exists($path)) {
            // Generate token for image
            $imageToken = substr(sha1(mt_rand()), 0, 5);
            return $filename . '-' . $imageToken . '.' . $extension;
        }

        return $filename . '.' . $extension;
    }

    /**
     * Optimize Original Image
     */
    public function original($video, $filename)
    {
        $image = $video->storeAs($this->save_path, $filename);
        return $image;
    }

    /**
     * Create Icon From Original
     */

    public function delete($filename)
    {
        $sessionVideo = Video::where('file_name', 'like', $filename)->first();

        if (empty($sessionVideo)) {
            $sessionVideo = Video::where('display_name', 'like', $filename)->first();
            if (empty($sessionVideo)) {
                return Response::json([
                    'status' => false,
                    'file_name' => $filename,
                    'code' => 400
                ], 400);
            }
        }
        $path = storage_path($this->full_save_path . $filename);
        if (File::exists($path)) {
            Storage::delete('uploads/images/' . $filename);
        }
        if (!empty($sessionVideo)) {
            $sessionVideo->delete();
        }
        return Response::json([
            'status' => true,
            'id' => $sessionVideo->id,
            'filename' => $filename,
            'message' => 'تم حذف الفيديو بنجاح',
            'code' => 200
        ], 200);
    }

    function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;

        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }
}
<?php

namespace App\Logic;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use \App\Image;
use \Storage;

use Illuminate\Http\Request;

class ImageRepository {

    private $full_save_path = 'app/uploads/images/';
    private $save_path = 'uploads/images/';
    public function upload( $form_data, $type ) {
        $validator = Validator::make($form_data, \App\Image::$rules,\App\Image::$messages);
        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => $validator->messages()->first(),
            ], 400);
        }
        $photo = $form_data['image'];
        $extension = $photo->getClientOriginalExtension();
        $filename = 'image_'.time().mt_rand();
        $image_size = $photo->getClientSize();
        $allowed_filename = $this->createUniqueFilename( $filename, $extension );
        $uploadSuccess1 = $this->original( $photo, $allowed_filename );
        $originalName = str_replace('.'.$extension, '', $photo->getClientOriginalName());
        if( !$uploadSuccess1 ) {
            return Response::json([
                'error' => true,
                'message' => 'Server error while uploading',
            ], 500);
        }
        $image = new Image;
        $image->display_name = $originalName.'.'.$extension;
        $image->file_name = $allowed_filename;
        $image->mime_type = $extension;
        $image->size = $image_size;
        $image->save();
        return Response::json([
            'status' => true,
            'file_name' => $allowed_filename,
            'display_name' => $originalName.'.'.$extension,
            'id' => $image->id
        ], 200);
    }

    public function createUniqueFilename( $filename, $extension ) {
        $path = storage_path($this->full_save_path . $filename . '.' . $extension);


        if ( File::exists( $path ) )
        {
            $imageToken = substr(sha1(mt_rand()), 0, 5);
            return $filename . '-' . $imageToken . '.' . $extension;
        }

        return $filename . '.' . $extension;
    }

    /**
     * Optimize Original Image
     */
    public function original( $photo, $filename ) {
        $image = $photo->storeAs($this->save_path, $filename);
        return $image;
    }

    /**
     * Create Icon From Original
     */
    public function icon( $photo, $filename )
    {
        $manager = new ImageManager();
        $image = $manager->make( $photo )->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save( $this->icon_save_path  . $filename );
        return $image;
    }

    public function delete($filename) {
        $sessionImage =  Image::where('file_name', 'like', $filename)->first();

        if(empty($sessionImage))
        {
            $sessionImage  =  Image::where('display_name', 'like', $filename)->first();
            if(empty($sessionImage)) {
                return Response::json([
                    'error' => true,
                    'file_name' => $filename,
                    'code' => 400
                ], 400);
            }
        }
        $path = storage_path($this->full_save_path . $filename );
        if ( File::exists( $path ) ) {
            Storage::delete('uploads/images/'. $filename);
        }
        if( !empty($sessionImage)) {
            $sessionImage->delete();
        }
        return Response::json([
            'error' => false,
            'id' => $sessionImage->id,
            'filename' => $filename,
            'message' => 'Image Deleted',
            'code'  => 200
        ], 200);
    }

    function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }
}
<?php
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect()->route('front.home');
})->name('login');


Route::prefix('/image')->group(function () {
    Route::post('/upload', ['as' => 'upload.image', 'uses' => 'ImageController@upload_image']);
    Route::post('/delete', ['as' => 'delete.image', 'uses' => 'ImageController@delete_image']);
    Route::get('/{size}/{id}', ['as' => 'image', 'uses' => 'ImageHandler@getPublicImage']);
    Route::get('/limit/{size}/{id}', ['as' => 'image', 'uses' => 'ImageHandler@getImageResize']);
    Route::get('/{id}', ['as' => 'image', 'uses' => 'ImageHandler@getDefaultImage']);
});

Route::prefix('/video')->group(function () {
    Route::post('/upload', ['as' => 'video.upload', 'uses' => 'VideoController@uploadVideo']);
    Route::post('/delete', ['as' => 'video.delete', 'uses' => 'VideoController@deleteVideo']);
    Route::get('/{id}', 'VideoController@getVideo');
});
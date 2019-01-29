<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', ['as' => 'test', 'uses' => 'HomeController@test']);


Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('/privacy', ['as' => 'privacy', 'uses' => 'HomeController@index']);
Route::get('/terms', ['as' => 'terms', 'uses' => 'HomeController@index']);

Route::group(['prefix' => '/register', 'as' => 'register.'], function () {
    Route::post('/', ['as' => 'post', 'uses' => 'Auth\RegisterController@registerUser']);
});

Route::group(['prefix' => '/login', 'as' => 'login.'], function () {
    Route::post('/', ['as' => 'post', 'uses' => 'Auth\LoginController@login']);
    Route::group(['prefix' => '/facebook', 'as' => 'facebook.'], function () {
        Route::get('/', ['as' => 'auth', 'uses' => 'Auth\LoginController@redirectToProvider']);
        Route::get('/callback', ['as' => 'callback', 'uses' => 'Auth\LoginController@handleProviderCallback']);
    });
});

Route::group(['prefix' => '/feeds', 'as' => 'feeds.'], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'FeedsController@index']);
    Route::group(['prefix' => '/posts', 'as' => 'posts.'], function () {
        Route::get('/{type}/{id}', ['as' => 'data', 'uses' => 'FeedsController@getPosts']);
        Route::post('/', ['as' => 'store', 'uses' => 'FeedsController@publishPost']);
        Route::post('/{id}/like', ['as' => 'store', 'uses' => 'FeedsController@postLike']);
        Route::post('/{id}/share', ['as' => 'store', 'uses' => 'FeedsController@postShare']);
        Route::get('/{id}/share/get', ['as' => 'index', 'uses' => 'FeedsController@shareIndex']);
    });

    Route::group(['prefix' => '/comment', 'as' => 'comment.'], function () {
        Route::post('/', ['as' => 'post', 'uses' => 'FeedsController@postComment']);
    });
});


Route::group(['prefix' => '/profile', 'as' => 'profile.'], function () {
    Route::get('{id}/followers', ['as' => 'followers', 'uses' => 'ProfileController@followersIndex']);
    Route::get('{id}/following', ['as' => 'following', 'uses' => 'ProfileController@followingIndex']);
    Route::patch('{id}/follow', ['as' => 'follow', 'uses' => 'ProfileController@follow']);
    Route::get('{id}/show', ['as' => 'show', 'uses' => 'ProfileController@show']);
    Route::group(['prefix' => '/edit', 'as' => 'edit.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ProfileController@editIndex']);
        Route::put('/', ['as' => 'update', 'uses' => 'ProfileController@updateProfile']);
    });
});
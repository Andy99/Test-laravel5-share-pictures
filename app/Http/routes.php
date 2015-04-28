<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Auth **********
Route::get('/', 'AuthController@index');
Route::post('login-fb', 'AuthController@loginWithFacebook');
Route::post('auth/register', array('as' => 'auth.register', 'uses' => 'AuthController@postRegister'));
Route::get('home-fb', 'AuthController@handleProviderCallback');

// Home **********
Route::get('home', 'HomeController@index');
Route::get('edit-profile', 'HomeController@showEditProfile');
Route::patch('edit-profile/{id}', array( 'as' => 'edit-profile', 'uses' => 'HomeController@editProfile'));
Route::get('search_people', 'HomeController@searchPeople');
Route::get('pictures/edit/{id?}', 'HomeController@editPictures');
Route::get('pictures/{username?}', 'HomeController@getPictures');
Route::post('homeScrolling',  array('as' => 'homeScrolling', 'uses' => 'HomeController@getScrollingData'));

Route::post('add_pictures', array('as' => 'add_pictures', 'uses' => 'HomeController@addPictures'));
Route::patch('add_picture_title/{id}', array('as' => 'add_picture_title', 'uses' => 'HomeController@addTitlePictures'));
Route::get('picture/delete/{id}', array('as' => 'picture.delete', 'uses' => 'HomeController@deletePictures'));
Route::post('add_follower', 'HomeController@addFollower');
Route::post('remove_follower', 'HomeController@removeFollower');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

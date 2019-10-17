<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
URL::forceScheme('https');
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}



Route::get('/', function () {
    return view('welcome');
});

Route::post('login/custom' ,[
  'uses' => 'CustomLoginController@login',
  'as' => 'login.custom'
]);

Route::post('editpage/rm' , 'EditPageController@destroy');
Route::post('editpage/edit' , 'EditPageController@edit');
Route::post('editpage/update' , 'EditPageController@update');
Route::post('demo/search' , 'ContentController@search');

Route::group(['middleware' => 'auth'] , function(){

  Route::resource('cardboard' , 'CrouseController');

  Route::resource('demo' , 'ContentController');

  Route::resource('editpage' , 'EditPageController');

  Route::resource('uploadfile' , 'FileController');

  Route::resource('feedback' , 'FeedbackController');


});

Route::post('feedback/filter' , 'FeedbackController@filter');
Route::post('feedback/browse' , 'FeedbackController@browse');
Route::resource('feedback' , 'FeedbackController');





//Route::post('demo',['as'=>'demo.content','uses'=>'ContentController@store']);
//Route::get('demo',array('as'=>'demo.get','uses'=>'ContentController@index'));

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

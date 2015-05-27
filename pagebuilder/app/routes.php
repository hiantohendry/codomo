<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/',array('uses' => 'HomeController@index'));

Route::get('home',array('uses' => 'HomeController@index'));

Route::match(array('GET', 'POST'),'upload',array('uses' => 'HomeController@doUpload'));

Route::match(array('GET', 'POST'),'newpage',array('uses' => 'HomeController@newPage'));

Route::match(array('GET', 'POST'),'savepage',array('uses' => 'HomeController@savePage'));

Route::match(array('GET', 'POST'),'loadpage',array('uses' => 'HomeController@loadPage'));

Route::match(array('GET', 'POST'),'page',array('uses' => 'HomeController@publishPage'));

Route::match(array('GET', 'POST'),'list',array('uses' => 'HomeController@listPage'));

Route::match(array('GET', 'POST'),'deleteimage',array('uses' => 'HomeController@deleteImage'));

Route::match(array('GET', 'POST'),'publish/loadpage',array('uses' => 'HomeController@loadPage'));

Route::match(array('GET', 'POST'),'publish/{id}',array('uses' => 'HomeController@publish'));
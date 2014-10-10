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

Route::get('/', array("as" => "home", "uses" => 'HomeController@index'));
Route::get('/logout', array("as" => "logout", "uses" => 'HomeController@logout'));
Route::get('/auth', array("as" => "auth", "uses" => 'HomeController@auth'));

Route::get('/me', array("as" => "dashboard_index", "uses" => 'DashboardController@index'));

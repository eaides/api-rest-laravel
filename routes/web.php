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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RouteFileRegistrar;

$disable_web_routes = config('app.disable_web_routes');
$use_email_verification = config('app.use_email_verification');
$api_prefix = config('app.api_prefix');

if ($disable_web_routes)
{
    // @todo replace all by API functions

    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    // Password Confirmation Routes...
    // @todo??       class_exists($this->prependGroupNamespace('Auth\ConfirmPasswordController'))) {
    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

    // Do not use Laravel Email Verification Routes...
    // we have already implemented those routes by API methods

    // frontend section
    Route::get('/home/my-tokens', 'HomeController@getTokens')->name('personal-tokens');
    Route::get('/home/my-clients', 'HomeController@getClients')->name('personal-clients');
    Route::get('/home/authorized-clients', 'HomeController@getAuthorizedClients')->name('authorized-clients');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/', function () {
        return view('welcome');
    })->middleware('guest');

    return;
}

Route::get('/', function () {
    return view('welcome');
});

if ($use_email_verification)
{
    Auth::routes(['verify' => true]);
}
else
{
    Auth::routes(['verify' => false]);
}

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

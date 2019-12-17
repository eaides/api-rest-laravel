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

$disable_web_routes = config('app.disable_web_routes');
$use_email_verification = config('app.use_email_verification');

if ($disable_web_routes)
{
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

Route::get('/home', 'HomeController@index')->name('home');

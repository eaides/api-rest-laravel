<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*
 * Route for /api/users ***
 *      except function create() that render form for create and 'edit'
 */
//Route::resource('users','User.UserController', ['except'=>['create','edit']]);
//Route::resource('categories','Category.CategoryController', ['except'=>['create','edit']]);
//Route::resource('posts','Post.PostController', ['except'=>['create','edit']]);

// apiResource automatically excludes 'create','edit'
Route::apiResource('users','User\UserController', ['except'=>['create','edit']]);
Route::apiResource('categories','Category\CategoryController', ['except'=>['create','edit']]);
Route::apiResource('posts','Post\PostController', ['except'=>['create','edit']]);

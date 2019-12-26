<?php

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

/*
 * Users
 *
 * Route for /api/users ***
 *      except function create() that render form for create and 'edit' that render form for edit
 */
Route::name('api.verify')->get('users/verify/{token}', 'User\UserController@verify');
// use apiResource automatically that excludes 'create','edit'
Route::apiResource('users','User\UserController');
//Route::resource('users','User\UserController', ['except'=>['create','edit']]);
//Route::resource('categories','Section\SectionController', ['except'=>['create','edit']]);
//Route::resource('posts','Post\PostController', ['except'=>['create','edit']]);

/**
 * Section
 */
Route::apiResource('sections','Section\SectionController');

/**
 * Post
 */
Route::apiResource('posts','Post\PostController');

/**
 * Buyers
 */
Route::apiResource('buyers','Buyer\BuyerController', ['only'=>['index','show']]);
Route::apiResource('buyers.categories','Buyer\BuyerCategoryController', ['only'=>['index']]);
Route::apiResource('buyers.products','Buyer\BuyerProductController', ['only'=>['index']]);
Route::apiResource('buyers.sellers','Buyer\BuyerSellerController', ['only'=>['index']]);
Route::apiResource('buyers.transactions','Buyer\BuyerTransactionController', ['only'=>['index']]);

Route::apiResource('categories','Category\CategoryController', ['except'=>['create','edit']]);
Route::apiResource('categories.buyers','Category\CategoryBuyerController', ['only'=>['index']]);
Route::apiResource('categories.products','Category\CategoryProductController', ['only'=>['index']]);
Route::apiResource('categories.sellers','Category\CategorySellerController', ['only'=>['index']]);
Route::apiResource('categories.transactions','Category\CategoryTransactionController', ['only'=>['index']]);

Route::apiResource('products','Product\ProductController', ['only'=>['index','show']]);





/**
 * Sellers
 */
Route::apiResource('sellers','Seller\SellerController', ['only'=>['index','show']]);
Route::apiResource('sellers.buyers','Seller\SellerBuyerController', ['only'=>['index']]);
Route::apiResource('sellers.categories','Seller\SellerCategoryController', ['only'=>['index']]);
Route::apiResource('sellers.products','Seller\SellerProductController', ['except'=>['show']]);
Route::apiResource('sellers.transactions','Seller\SellerTransactionController', ['only'=>['index']]);

/**
 * Transactions
 */
Route::apiResource('transactions','Transaction\TransactionController', ['only'=>['index','show']]);
Route::apiResource('transactions.categories','Transaction\TransactionCategoryController', ['only'=>['index']]);
Route::apiResource('transactions.sellers','Transaction\TransactionSellerController', ['only'=>['index']]);

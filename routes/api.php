<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\Helper;

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
if (Helper::needApiValidation()) {
    Route::name('api.resend')->get('users/{user}/resend', 'User\UserController@resend');
}
Route::name('api.verify')->get('users/verify/{token}', 'User\UserController@verify');
// use apiResource automatically that excludes 'create','edit'
// Route::resource('users','User\UserController', ['except'=>['create','edit']]);
Route::apiResource('users','User\UserController');

/**
 * Section
 */
Route::apiResource('sections','Section\SectionController');
Route::apiResource('sections.posts','Section\SectionPostController', ['except'=>['show']]);

/**
 * Post
 */
// use apiResource automatically that excludes 'create','edit'
// Route::resource('posts','Post\PostController', ['except'=>['create','edit']]);
Route::apiResource('posts','Post\PostController', ['only'=>['index','show']]);

/**
 * Buyer
 */
Route::apiResource('buyers','Buyer\BuyerController', ['only'=>['index','show']]);
Route::apiResource('buyers.categories','Buyer\BuyerCategoryController', ['only'=>['index']]);
Route::apiResource('buyers.products','Buyer\BuyerProductController', ['only'=>['index']]);
Route::apiResource('buyers.sellers','Buyer\BuyerSellerController', ['only'=>['index']]);
Route::apiResource('buyers.transactions','Buyer\BuyerTransactionController', ['only'=>['index']]);

/**
 * Category
 */
// use apiResource automatically that excludes 'create','edit'
// Route::resource('categories','Section\SectionController', ['except'=>['create','edit']]);
Route::apiResource('categories','Category\CategoryController', ['except'=>['create','edit']]);
Route::apiResource('categories.buyers','Category\CategoryBuyerController', ['only'=>['index']]);
Route::apiResource('categories.products','Category\CategoryProductController', ['only'=>['index']]);
Route::apiResource('categories.sellers','Category\CategorySellerController', ['only'=>['index']]);
Route::apiResource('categories.transactions','Category\CategoryTransactionController', ['only'=>['index']]);

/**
 * Product
 */
Route::apiResource('products','Product\ProductController', ['only'=>['index','show']]);
Route::apiResource('products.buyers','Product\ProductBuyerController', ['only'=>['index']]);
Route::apiResource('products.buyers.transactions','Product\ProductBuyerTransactionController', ['only'=>['store']]);
Route::apiResource('products.categories','Product\ProductCategoryController', ['only'=>['index','update','destroy']]);
Route::apiResource('products.transactions','Product\ProductTransactionController', ['only'=>['index']]);

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

// add here the ouath post call to ensure to use the api middleware and not just the throttle middleware
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');

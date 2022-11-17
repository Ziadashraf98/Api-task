<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::get('/get_tags' , [TagController::class , 'get_tags']);
    Route::post('/add_tag' , [TagController::class , 'add_tag']);
    Route::put('/update_tag/{id}' , [TagController::class , 'update_tag']);
    Route::delete('/delete_tag/{id}' , [TagController::class , 'delete_tag']);
});

Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::get('/pinned_posts' , [PostController::class , 'pinned_posts']);
    Route::get('/user_posts' , [PostController::class , 'user_posts']);
    Route::get('/single_post/{id}' , [PostController::class , 'single_post']);
    Route::post('/add_post' , [PostController::class , 'add_post']);
    Route::post('/update_post/{id}' , [PostController::class , 'update_post']);
    Route::delete('/delete_post/{id}' , [PostController::class , 'delete_post']);
    Route::get('/show_delete_posts/{id}' , [PostController::class , 'show_delete_posts']);
    Route::get('/restore_post/{id}' , [PostController::class , 'restore_post']);
});

Route::post('/login' , [UserController::class , 'login']);
Route::post('/register' , [UserController::class , 'register']);

Route::get('/users_number' , [StatsController::class , 'users_number']);
Route::get('/posts_number' , [StatsController::class , 'posts_number']);
Route::get('/users_number_of_0_posts' , [StatsController::class , 'users_number_of_0_posts']);
Route::get('/users_number_of_posts' , [StatsController::class , 'users_number_of_posts']);


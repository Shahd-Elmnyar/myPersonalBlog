<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//get Article
Route::get('/articles',[ArticleController::class,'index']) ;
//show Article
Route::get('/articles/show/{id}',[ArticleController::class,'show']);
//add Article
Route::post('/articles/store', [ArticleController::class, 'store']);
//update Article
Route::post('/articles/update/{id}', [ArticleController::class ,'update']);
//delete Article
Route::get('/articles/delete/{id}', [ArticleController::class ,'delete']);

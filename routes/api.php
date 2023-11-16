<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/newsapi', function () {
    $r = \App\NewsReaders\NewsReaderNewsApi::read();
    return $r->json();
});

Route::get('/gardian', function () {
    $r = \App\NewsReaders\NewsReaderGuardianApis::read();
    return ($r->json());
});

Route::get('/gardian/1', function () {
    $r = \App\NewsReaders\NewsReaderGuardianApis::single();
    return ($r->json());
});

Route::get('/world-news', function () {
    $r = \App\NewsReaders\NewsReaderWorldNews::read();
    return ($r->json());
});

<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $newsReader = resolve(\App\NewsReaders\NewsReaderManager::class);

    $category = \App\Models\Category::whereName('health')->first();
    $newsReader->driver('worldnews')->setFrom(now()->subDays(2))->setCategory($category)->pullNewsToDb();
    return view('welcome');
});

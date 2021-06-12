<?php

use App\Http\Controllers\uploadcontroller;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('uploads');
// });

Route::get('/', [uploadcontroller::class, 'create']);
Route::post('/store', [uploadcontroller::class, 'store'])->name('store');
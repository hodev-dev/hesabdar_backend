<?php

use App\Models\Label;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\SectionController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/add_section', [SectionController::class,'add_section']);
Route::get('/get_section', [SectionController::class,'get_section']);
Route::post('/remove_section', [SectionController::class,'remove_section']);
Route::post('/update_section', [SectionController::class,'update_section']);
Route::get('/get_label', [LabelController::class,'get_label']);
Route::post('/add_label', [LabelController::class,'add_label']);
Route::post('/remove_label', [LabelController::class,'remove_label']);

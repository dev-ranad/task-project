<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'index');
Route::post('/store', 'store');
Route::get('/show/{id}', 'show');
Route::put('/update/{id}', 'update');
Route::delete('/delete/{id}', 'delete');

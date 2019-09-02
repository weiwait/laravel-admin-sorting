<?php

use Weiwait\Sorting\Http\Controllers\SortingController;
use \Illuminate\Support\Facades\Route;

//Route::get('sorting', SortingController::class.'@index');

Route::put('weiwait/sorting', [SortingController::class, 'sorting']);

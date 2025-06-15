<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;


Route::get('/', function () {
    return view('welcome');
});


// course add routes
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');



//frontend routes
Route::get('/', [CourseController::class, 'index'])->name('courses.index');
// here I kept  courses in the root url so that softvence team can easily access the courses without needing to specify a path. >>> 27.0.0.1:8000 will directly show the courses.

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/', [CourseController::class, 'index'])->name('courses.index');// here I kept courses in the root url so that softvence team can easily access the courses without needing to specify a path.

Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
Route::delete('/courses/delete/{id}', [CourseController::class, 'delete'])->name('courses.delete');

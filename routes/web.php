<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\MenuController;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(middleware: ['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

#User Profile
Route::get('/wm/user-profile',  [\App\Http\Controllers\Admin\UserController::class,'index'])->name('wm/user-profile');
Route::post('wm/user-profile/store', [\App\Http\Controllers\Admin\UserController::class,'store'])->name('wm/user-profile/store');
Route::post('wm/user-profile/delete/{id}', [\App\Http\Controllers\Admin\UserController::class,'destroy'])->name('wm/user-profile/delete');

     #Customer
     Route::get('/wm/customers',  [\App\Http\Controllers\Wms\CustomerController::class,'index'])->name('wm/customers');
     Route::post('wm/customers/store', [\App\Http\Controllers\Wms\CustomerController::class,'store'])->name('wm/customers/store');
     Route::post('wm/customer/delete/{id}', [\App\Http\Controllers\Wms\CustomerController::class,'destroy'])->name('wm/customers/delete');


    Route::resource('/menu', MenuController::class);
});

require __DIR__.'/auth.php';

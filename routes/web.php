<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;

// Home Route
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::get('/test', function () {
    return 'Route works!';
});


Route::get('/', [BlogController::class, 'home'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

#edit blogs by admin
Route::get('/admin/blogs/{id}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
Route::put('/admin/blogs/{id}', [BlogController::class, 'update'])->name('admin.blogs.update');
Route::delete('/admin/blogs/{id}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');

#add
Route::get('/admin/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/admin/blogs/store', [BlogController::class, 'store'])->name('admin.blogs.store');

// routes/web.php

// Route to display the category edit form
Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
// Route to handle the update of a category
Route::put('/admin/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
// Route to handle the deletion of a category
Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
// Route to display the create category form
Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
// Route to handle the submission of the create category form
Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');




Route::get('/blogs', [BlogController::class, 'index'])->name('blog');
Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blog.show');


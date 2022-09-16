<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactController;
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

Route::get('/', function () {
    return view('index');
});

//お問合せフォーム
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact', [ContactController::class, 'sendMail']);//バリデーション
Route::get('/contact/complete', [ContactController::class, 'complete'])->name('contact.complete');


//管理画面(ルートグループ)
Route::prefix('/admin')
    ->name('admin.')
    ->group(function()
    {
        //ログイン時のみアクセス可能なルート
        Route::middleware('auth')
        ->group(function()
        {
        //ブログ
        Route::resource('/blogs', AdminBlogController::class)->except('show');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });

        //未ログイン時のみアクセス可能なルート
        Route::middleware('guest')
        ->group(function(){
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        });
    });

//ユーザー管理
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users/', [UserController::class, 'store'])->name('admin.users.store');



/*ブログ (グループ化前のコード)
Route::get('/admin/blogs', [AdminBlogController::class, 'index'])->name('admin.blogs.index')->middleware('auth');
Route::get('/admin/blogs/create', [AdminBlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/admin/blogs', [AdminBlogController::class, 'store'])->name('admin.blogs.store');
Route::get('admin/blogs/{blog}', [AdminBlogController::class, 'edit'])->name('admin.blogs.edit');
Route::put('admin/blogs/{blog}', [AdminBlogController::class, 'update'])->name('admin.blogs.update');
Route::delete('admin/blogs/{blog}', [AdminBlogController::class, 'destroy'])->name('admin.blogs.destroy');
*/

/*認証(グループ化前のコード)
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
*/
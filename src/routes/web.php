<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;

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

// 商品一覧画面（トップ）・マイリスト付き
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/mylist', function () {
    return redirect()->route('items.index', ['tab' => 'mylist']);
})->name('items.mylist');

// 会員登録画面
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register']);

// メール認証用ルート（Laravel標準のメール認証）
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');  // メール認証促進画面を作成
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->intended('/mypage/profile'); // 認証後はプロフィール設定へ誘導
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送しました');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

// ログイン画面
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');
Route::post('/item/{id}/comment', [ItemController::class, 'storeComment'])->name('comment.store');
// いいね追加・削除
Route::post('/items/{item}/like-toggle', [ItemController::class, 'toggleLike'])->name('items.toggleLike');

// 商品購入画面
Route::middleware('auth')->group(function () {

Route::get('/purchase/{item_id}',
[PurchaseController::class, 'show'])->name('purchase.show');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase'])->name('purchase.store');
Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');

// 住所変更ページ
Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('address.update');

});

// 商品出品画面
Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

// プロフィール画面（デフォルト表示）
Route::get('/mypage', [UserController::class, 'show'])->name('user.profile');

// プロフィール編集画面
Route::get('/mypage/profile', [UserController::class, 'edit'])->name('user.profile.edit');
Route::put('/mypage/profile', [UserController::class, 'update'])->name('user.profile.update');

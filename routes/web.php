<?php

use App\Http\Controllers\HouseController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix("/user")->middleware(["auth"])->name("user.")->group(function () {
    Route::get('/houses/trash', [HouseController::class, 'trash'])->name('houses.trash');
    Route::get('/houses/{house}/sponsors}', [HouseController::class, 'sponsors'])->name('houses.sponsors');
    Route::get('/houses/{house}/sponsors/{sponsor}', [HouseController::class, 'sponsor'])->name('houses.sponsor');
    Route::post('/photoes', [PhotoController::class, 'store'])->name('photoes.store');
    Route::post('/houses/{house}/sponsors/{sponsor}/payment', [HouseController::class, 'payment'])->name('houses.payment');

    Route::patch('/houses/{house}/restore', [HouseController::class, 'restore'])->name('houses.restore');
    Route::patch('/houses/{house}/publish', [HouseController::class, 'publish'])->name('houses.publish');
    Route::delete('/houses/{house}/drop', [HouseController::class, 'drop'])->name('houses.drop');
    Route::resource("houses", HouseController::class);
});

require __DIR__ . '/auth.php';

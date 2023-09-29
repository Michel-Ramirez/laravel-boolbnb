<?php

use App\Http\Controllers\HouseController;
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
    Route::patch('/houses/{house}/restore', [HouseController::class, 'restore'])->name('houses.restore');
    Route::delete('/houses/{house}/drop', [HouseController::class, 'drop'])->name('houses.drop');
    Route::resource("houses", HouseController::class);
});

require __DIR__ . '/auth.php';

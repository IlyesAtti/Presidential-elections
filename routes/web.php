<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidatesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/next-round', [CandidatesController::class, 'nextRound'])->name('next-round');
    Route::post('/next-round', [CandidatesController::class, 'nextRound']);
    Route::get('/dashboard', [CandidatesController::class, 'show'])->name('dashboard');
    Route::post('/dashboard/vote', [CandidatesController::class, 'vote']);   
});

Route::get('/', [CandidatesController::class, 'showOnHome'])->name('home');
Route::get('/round/{round}', [CandidatesController::class, 'showOnHome'])->name('round.results');

require __DIR__.'/auth.php';
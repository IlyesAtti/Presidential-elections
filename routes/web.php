<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CandidatesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VotesController;
use App\Http\Controllers\RoundController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard/nextRound', [RoundController::class, 'nextRound'])->name('next-round');
    Route::post('/dashboard/nextRound', [RoundController::class, 'nextRound'])->name('nextRound');
    Route::get('/dashboard', [CandidatesController::class, 'show'])->name('dashboard');
    Route::post('/dashboard/vote', [VotesController::class, 'vote']);
    Route::post('/dashboard/revokeVote', [VotesController::class, 'revokeVote']);
    Route::post('dashboard/candidate', [CandidatesController::class, 'candidate'])->name('candidate');
    Route::post('/dashboard/revokeCandidate', [CandidatesController::class, 'revokeCandidate'])->name('revoke-candidate');

});

Route::get('/', [CandidatesController::class, 'showOnHome'])->name('home');
Route::get('/round/{round}', [CandidatesController::class, 'showOnHome'])->name('round.results');

require __DIR__.'/auth.php';
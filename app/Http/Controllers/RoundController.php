<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;

class RoundController extends Controller {
    public function nextRound(Request $req) {
        $currentRoundIndex = Candidate::max('roundIndex');
        if ($currentRoundIndex === null) {
            $currentRoundIndex = 1;
        }

        $newRoundIndex = $currentRoundIndex + 1;
        $user = Auth::user();
        
        Candidate::create([
            'userId' => $user->id,
            'userName' => $user->name,
            'roundIndex' => $newRoundIndex,
            'votes' => 0,
            'isVoted' => false,
            'votedFor' => 0,
        ]);

        return redirect()->route('dashboard', ['round' => $newRoundIndex]);
    }
}
